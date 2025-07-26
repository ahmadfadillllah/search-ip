<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\Cache;

class ArubaHelper
{
    /**
     * Ambil Guzzle Client, CookieJar, dan UID
     * Akan login ulang jika tidak ada cache, atau jika $forceLogin = true
     */
    public static function getClientWithLogin(bool $forceLogin = false): array
    {
        if (!$forceLogin) {
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
        }

        // Login ke Aruba Controller
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

        // Cache cookies dan uid
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
     * Jalankan perintah dan retry jika terjadi 401 (login ulang)
     */
    public static function fetchCommand(string $command): array
    {
        try {
            return self::executeCommand($command, false);
        } catch (ClientException | RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 401) {
                // Login ulang jika session habis
                return self::executeCommand($command, true);
            }

            // Error lain dilempar lagi
            throw $e;
        }
    }

    /**
     * Eksekusi perintah ke Aruba Controller
     */
    private static function executeCommand(string $command, bool $forceLogin): array
    {
        $session = self::getClientWithLogin($forceLogin);

        $response = $session['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
            'query' => [
                'command' => $command,
                'UIDARUBA' => $session['uid'],
            ],
            'verify' => false,
            'cookies' => $session['cookieJar'],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Bersihkan cache Aruba auth data (paksa login ulang di next request)
     */
    public static function clearCache(): void
    {
        Cache::forget('aruba_auth_data');
    }
}
