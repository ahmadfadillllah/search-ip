<?php

namespace App\Console\Commands;

use App\Models\Topology;
use Carbon\Carbon;
use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class InsertDataTopology extends Command
{
    protected $signature = 'api:insert-data-topology';
    protected $description = 'Insert data from API every hour';

    public function handle()
    {
        $client = new Client();
        $cookieJar = new CookieJar();

        // Login ke API
        $data_login = $client->post('https://10.10.2.12:4343/v1/api/login', [
            'form_params' => [
                'username' => env('USERNAME_ARUBA'),
                'password' => env('PASSWORD_ARUBA'),
                'action' => 'login'
            ],
            'verify' => false,
            'cookies' => $cookieJar
        ]);

        $headerSetCookies = $data_login->getHeader('Set-Cookie');
        $cookies = [];
        foreach ($headerSetCookies as $header) {
            $cookie = SetCookie::fromString($header);
            $cookie->setDomain(env('IP_ARUBA'));
            $cookies[] = $cookie;
        }

        $cookieJar = new CookieJar(false, $cookies);
        $cookiesArray = $cookieJar->toArray();
        $firstCookie = $cookiesArray[0];

        // Ambil data dari API
        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+mesh+topology+long&UIDARUBA=' . $firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        // Simpan data ke database
        foreach ($data['Mesh Cluster Name: mesh-aruba'] as $item) {
            if (!empty($item['Name'])) {
                Topology::create([
                    'STATUSENABLED' => true,
                    'DATETIME' => Carbon::now(),
                    'COUNT_CHILDREN' => $item['#Children'],
                    'CHILDREN' => $item['Children'],
                    'HOP_COUNT' => $item['Hop Count'],
                    'LAST_UPDATED' => $item['Last Update'],
                    'LINK_COST' => $item['Link Cost'],
                    'MESH_ROLE' => $item['Mesh Role'],
                    'NAME' => $item['Name'],
                    'NODE_COST' => $item['Node Cost'],
                    'PARENT' => $item['Parent'],
                    'PATH_COST' => $item['Path Cost'],
                    'RSSI' => $item['RSSI'],
                    // 'RATE_TX_RX' => $item['Rate Tx\/Rx'],
                    'UPLINK_AGE' => $item['Uplink Age'],
                    'CREATED_BY' => 'SYSTEM',
                    'CREATED_AT' => Carbon::now(),
                ]);
            }
        }

        $this->info('Data inserted successfully.');
    }
}

