<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    //
    public function index()
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

        // $ip_client = Unit::select('no_unit', 'ip')->get();

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

        $datas = [
            'data' => $data['Users'],
            'datagroup' => collect($data['Users'])->groupBy('AP name'),
        ];


        return view('client.index', compact('data', 'datas'));

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

        $data = $data['Users'];

        return response()->json($data);

    }
}
