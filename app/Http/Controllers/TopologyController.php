<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class TopologyController extends Controller
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+mesh+topology+long&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        $data = collect($data['Mesh Cluster Name: mesh-aruba'])->where('Name', '!=', "")->groupBy('Name');
        return view('topology.index', compact('data'));
    }
}
