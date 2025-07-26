<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\DB;
use App\Helpers\ArubaHelper;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ClientController extends Controller
{
    //
    public function index()
    {
        try {
            // Ambil client login dari helper
            $aruba = ArubaHelper::getClientWithLogin();

            // Request data user-table dari Aruba
            $response = $aruba['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
                'query' => [
                    'command' => 'show user-table',
                    'UIDARUBA' => $aruba['uid']
                ],
                'cookies' => $aruba['cookieJar'],
                'verify' => false,
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            // Ambil IP dari Focus DB
            $ip_client = DB::connection('focus')
                ->table('FLT_VEHICLE')
                ->select([
                    'VHC_ID as no_unit',
                    'NET_IPADDRESS as ip',
                ])
                ->where('VHC_ACTIVE', true)
                ->get();

            // Mapping nama client jika kosong
            foreach ($data['Users'] as &$item) {
                if ($item['Name'] === null) {
                    $unit = $ip_client->where('ip', $item['IP'])->first();
                    $item['Name'] = $unit->no_unit ?? 'Client tidak terdaftar';
                }
            }

            $datas = [
                'data' => $data['Users'],
                'datagroup' => collect($data['Users'])->groupBy('AP name'),
            ];

            return view('client.index', compact('data', 'datas'));

        } catch (ClientException | RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 401) {
                ArubaHelper::clearCache();
                return redirect()->back()->with('info', 'Sesi login Aruba habis. Harap mencoba kembali 30 detik - 1 menit');
            }

            return redirect()->back()->with('info', 'Gagal mengambil data client Aruba: ' . $e->getMessage());

        } catch (\Throwable $th) {
            return redirect()->route('dashboard.index')->with('info', 'Gagal mengambil data client Aruba.');
        }

    }

    public function show($name)
    {
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+user-table&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        $ip_client = DB::connection('sqlsrv')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID as no_unit',
                        'NET_IPADDRESS as ip',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();

        foreach ($data['Users'] as &$item) {
            if ($item['Name'] === null) {
                // Lakukan pencarian no_unit berdasarkan IP
                $unit = $ip_client->where('ip', $item['IP'])->first();

                // Set Name ke no_unit jika unit ditemukan berdasarkan IP
                if ($unit) {
                    $item['Name'] = $unit->no_unit;
                } else {
                    $item['Name'] = "Client tidak terdaftar"; // Jika tidak ditemukan, beri nilai default "Testing"
                }
            }
        }



        $data = [
            'datas' => collect($data['Users'])->where('AP name', $name),
            'ap_name' => $name,
            'total' => collect($data['Users'])->where('AP name', $name)->count()
        ];


        return view('client.show', compact('data'));
    }

    public function api()
    {
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+user-table&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        $ip_client = DB::connection('focus')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID as no_unit',
                        'NET_IPADDRESS as ip',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();

        foreach ($data['Users'] as &$item) {
            if ($item['Name'] === null) {
                // Lakukan pencarian no_unit berdasarkan IP
                $unit = $ip_client->where('ip', $item['IP'])->first();

                // Set Name ke no_unit jika unit ditemukan berdasarkan IP
                if ($unit) {
                    $item['Name'] = $unit->no_unit;
                } else {
                    $item['Name'] = "Client tidak terdaftar"; // Jika tidak ditemukan, beri nilai default "Testing"
                }
            }
        }

        $data = $data['Users'];

        return response()->json($data);

    }
}
