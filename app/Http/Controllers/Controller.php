<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function doLogin()
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

            return $cookies;
        }
}
