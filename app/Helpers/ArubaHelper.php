<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\Cache;

class ArubaHelper
{
    /**
     * Ambil Guzzle Client, CookieJar, dan UID, login jika belum ada di cache
     */
    public static function getClientWithLogin(): array
    {
        $cached = Cache::get('aruba_auth_data');

        if ($cached && isset($cached['cookies'], $cached['uid'])) {
            $client = new Client();
            $cookieJar = new CookieJar(false, $cached['cookies']);
            return [
                'client' => $client,
                'cookieJar' => $cookieJar,
                'uid' => $cached['uid'],
            ];
        }

        // Jika belum login atau cache kadaluarsa, lakukan login
        $client = new Client();
        $cookieJar = new CookieJar();

        $loginResponse = $client->post('https://' . env('IP_ARUBA') . ':4343/v1/api/login', [
            'form_params' => [
                'username' => env('USERNAME_ARUBA'),
                'password' => env('PASSWORD_ARUBA'),
                'action' => 'login',
            ],
            'verify' => false,
            'cookies' => $cookieJar,
        ]);

        $headerSetCookies = $loginResponse->getHeader('Set-Cookie');
        $cookies = [];

        foreach ($headerSetCookies as $header) {
            $cookie = SetCookie::fromString($header);
            $cookie->setDomain(env('IP_ARUBA'));
            $cookies[] = $cookie;
        }

        $cookieJar = new CookieJar(false, $cookies);
        $cookiesArray = $cookieJar->toArray();
        $uid = $cookiesArray[0]['Value'] ?? null;

        if (!$uid) {
            throw new \Exception('Gagal login ke Aruba Controller: UID tidak ditemukan.');
        }

        // Simpan cookies dan uid ke cache (hanya data serializable)
        Cache::put('aruba_auth_data', [
            'cookies' => $cookies,
            'uid' => $uid,
        ], now()->addMinutes(60));

        return [
            'client' => $client,
            'cookieJar' => $cookieJar,
            'uid' => $uid,
        ];
    }

    /**
     * Ambil data dari Aruba Controller berdasarkan command
     */
    public static function fetchCommand(string $command): array
    {
        $data = self::getClientWithLogin();

        $response = $data['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
            'query' => [
                'command' => $command,
                'UIDARUBA' => $data['uid'],
            ],
            'verify' => false,
            'cookies' => $data['cookieJar'],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * (Opsional) Clear cache untuk paksa login ulang
     */
    public static function clearCache(): void
    {
        Cache::forget('aruba_auth_data');
    }
}
