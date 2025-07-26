<?php

namespace App\Http\Controllers;

use App\Models\Topology;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use App\Helpers\ArubaHelper;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class TopologyController extends Controller
{
    //
    public function index()
    {
        try {
            // Ambil client, cookieJar, dan uid dari helper (dengan cache)
            $aruba = ArubaHelper::getClientWithLogin();

            // Lakukan request ke Aruba
            $response = $aruba['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
                'query' => [
                    'command' => 'show ap mesh topology long',
                    'UIDARUBA' => $aruba['uid']
                ],
                'cookies' => $aruba['cookieJar'],
                'verify' => false,
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            // Proses data
            $data_topology = collect($data['Mesh Cluster Name: mesh-aruba'] ?? [])
                ->where('Name', '!=', "")
                ->groupBy('Name');

            $data_tree = collect($data['Mesh Cluster Name: mesh-aruba'] ?? [])
                ->where('Name', '!=', "")
                ->groupBy('Parent');

            return view('topology.index', compact('data_topology', 'data_tree'));

        } catch (ClientException | RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 401) {
                ArubaHelper::clearCache();
                return redirect()->back()->with('info', 'Sesi login Aruba habis. Harap mencoba kembali 30 detik - 1 menit');
            }

            return redirect()->back()->with('info', 'Gagal mengambil data topology Aruba: ' . $e->getMessage());

        } catch (\Throwable $th) {
            return redirect()->route('dashboard.index')->with('info', 'Gagal mengambil data topology Aruba.');
        }
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+mesh+topology+long&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        $data_topology = collect($data['Mesh Cluster Name: mesh-aruba'])->where('Name', '!=', "")->groupBy('Name');
        $data_tree = collect($data['Mesh Cluster Name: mesh-aruba'])->where('Name', '!=', "")->groupBy('Parent');


        $data = [
            'data_topology' => $data_topology,
            'data_tree' => $data_tree,
        ];
        return response()->json($data);
    }

        public function api_history(Request $request)
        {
            $startDate = $request->startDate;
            $startHour = $request->startHour;

            $start = Carbon::parse("$startDate $startHour:00:00");
            $end = (clone $start)->addHour();

            $data = Topology::where('STATUSENABLED', true)
                ->whereBetween('DATETIME', [$start, $end])
                ->get();

            return response()->json($data);
        }
}
