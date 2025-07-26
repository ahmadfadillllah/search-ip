<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Client;
use App\Helpers\ArubaHelper;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $unit = DB::connection('focus')
            ->table('FLT_VEHICLE')
            ->select(['VHC_ID', 'EQU_TYPEID', 'NET_IPADDRESS', 'APP_VERSION'])
            ->where('VHC_ACTIVE', true)
            ->get();

        // 2. Ambil status unit
        $statusUnit = collect(DB::connection('focus')->select('SET NOCOUNT ON; EXEC FOCUS_REPORTING.DBO.RPT_DASHBOARD_RESUME_TOTAL_UNIT'));

        // 3. Login Aruba sekali melalui helper
        $aruba = ArubaHelper::getClientWithLogin();
        $client = $aruba['client'];
        $cookieJar = $aruba['cookieJar'];
        $uidAruba = $aruba['uid'];

        // 4. Fetch data dari Aruba menggunakan UID
        $commands = [
            'show ap active' => 'Active AP Table',
            'show ap database long' => 'AP Database',
            'show user-table' => 'Users',
        ];

        $dataAruba = [];


        foreach ($commands as $command => $responseKey) {
            try {
                $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand', [
                    'query' => [
                        'command' => $command,
                        'UIDARUBA' => $uidAruba
                    ],
                    'verify' => false,
                    'cookies' => $cookieJar,
                ]);

                $body = json_decode($response->getBody(), true);
                $dataAruba[$responseKey] = collect($body[$responseKey] ?? []);

            } catch (ClientException | RequestException $e) {
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 401) {
                    ArubaHelper::clearCache();
                    return redirect()->back()->with('info', 'Sesi login Aruba habis. Harap mencoba kembali 30 detik - 1 menit');
                }

                return redirect()->back()->with('info', 'Client error: ' . $e->getMessage());
            } catch (\Throwable $e) {
                return redirect()->back()->with('info', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        $type_aruba = $dataAruba['Active AP Table'];
        $aruba = $dataAruba['AP Database'];
        $device = $dataAruba['Users'];

        // 5. Ambil data ritasi
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $ritasi = collect(DB::connection('focus')->select(
            'SET NOCOUNT ON; EXEC FOCUS_REPORTING.dbo.APP_RATE_PER_HOUR_RESUMEDATA @DATE = ?',
            [$date]
        ));

        // 6. Hitung status ritasi realtime vs total
        $statusRitation = DB::connection('focus')->select("
            SELECT
                CAST(OPR_REPORTTIME AS DATE) AS report_date,
                COUNT(CASE WHEN DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) <= 300 THEN 1 END) AS realtime,
                COUNT(*) AS total
            FROM PRD_RITATION WITH (NOLOCK)
            WHERE OPR_REPORTTIME BETWEEN ? AND ?
            GROUP BY CAST(OPR_REPORTTIME AS DATE)
            ORDER BY report_date ASC
        ", [
            Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'),
            Carbon::now()->addDay()->format('Y-m-d 23:59:59')
        ]);

        $realtimeDataRitation = [];
        $totalDataRitation = [];

        foreach ($statusRitation as $row) {
            $timestamp = strtotime($row->report_date) * 1000;
            $realtimeDataRitation[] = ['x' => $timestamp, 'y' => (int) $row->realtime];
            $totalDataRitation[] = ['x' => $timestamp, 'y' => (int) $row->total];
        }

        // 7. Kirim ke view
        return view('dashboard.index', compact(
            'unit',
            'statusUnit',
            'type_aruba',
            'aruba',
            'device',
            'ritasi',
            'realtimeDataRitation',
            'totalDataRitation'
        ));
    }

    public function api()
    {
        $unit = DB::connection('sqlsrv')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID',
                        'EQU_TYPEID',
                        'NET_IPADDRESS',
                        'APP_VERSION',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();

        $statusUnit = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.DBO.RPT_DASHBOARD_RESUME_TOTAL_UNIT');
        $statusUnit = collect($statusUnit);
        // dd($statusUnit);


        $client = new \GuzzleHttp\Client();
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar();

        $data_login = $client->post('https://10.10.2.12:4343/v1/api/login', [
            'form_params' => [
                'username' => env('USERNAME_ARUBA'),
                'password' => env('PASSWORD_ARUBA'),
                'action' => 'login'
            ],
            'verify' => false,
            'cookies' => $cookieJar
        ]
        );


        $headerSetCookies = $data_login->getHeader('Set-Cookie');

        $cookies = [];
        foreach ($headerSetCookies as $key => $header) {
            $cookie = SetCookie::fromString($header);
            $cookie->setDomain(env('IP_ARUBA'));

            $cookies[] = $cookie;
        }
        $cookieJar = new CookieJar(false, $cookies);

        $cookiesArray = $cookieJar->toArray();
        $firstCookie = $cookiesArray[0];

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+active&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);


        $body = $response->getBody()->getContents();
        $type_aruba = json_decode($body, true);
        $type_aruba = collect($type_aruba['Active AP Table']);

        $responseDevice = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+user-table&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $bodyDevice = $responseDevice->getBody()->getContents();
        $device = json_decode($bodyDevice, true);
        $device = collect($device['Users']);



        $data = [
            'unit' => $unit,
            'type_aruba' => $type_aruba,
            'device' => $device,
            'statusUnit' => $statusUnit,
        ];

        $result = array(
            "data" => $data,
            "status" => 200,
            "message" => "Success",
        );
        return response()->json($result, $result['status']);
    }
}
