<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $unit = DB::connection('focus')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID',
                        'EQU_TYPEID',
                        'NET_IPADDRESS',
                        'APP_VERSION',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();


        $statusUnit = DB::connection('focus')->select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.DBO.RPT_DASHBOARD_RESUME_TOTAL_UNIT');
        $statusUnit = collect($statusUnit);


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

        $responseAP = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+database+long&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);
        $bodyAP = $responseAP->getBody()->getContents();
        $aruba = json_decode($bodyAP, true);
        $aruba = collect($aruba['AP Database']);

        $responseDevice = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+user-table&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $bodyDevice = $responseDevice->getBody()->getContents();
        $device = json_decode($bodyDevice, true);
        $device = collect($device['Users']);

        $now = new DateTime();
        $date = $now->format('Y-m-d');

        $ritasi = DB::connection('focus')->select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.APP_RATE_PER_HOUR_RESUMEDATA @DATE = ?', [$date]);
        $ritasi = collect($ritasi);

        return view('dashboard.index', compact('unit', 'type_aruba', 'device', 'statusUnit', 'aruba', 'ritasi'));
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
