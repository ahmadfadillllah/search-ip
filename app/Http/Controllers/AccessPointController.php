<?php

namespace App\Http\Controllers;

use App\Models\LogReboot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Symfony\Component\Process\Process;
use Graze\TelnetClient\TelnetClient;
use App\Helpers\ArubaHelper;

class AccessPointController extends Controller
{
    //
    public function index()
    {
        try {
            $aruba = ArubaHelper::getClientWithLogin();

            // Request data show ap database long
            $response = $aruba['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
                'query' => [
                    'command' => 'show ap database long',
                    'UIDARUBA' => $aruba['uid']
                ],
                'cookies' => $aruba['cookieJar'],
                'verify' => false,
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            $data = collect($data['AP Database'])->sortBy('Status');

            return view('access_point.index', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->route('dashboard.index')->with('info', 'Gagal mengambil data Access Point.');
        }

    }

    public function reboot(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'apName' => 'required|string',
            'statusAP' => 'required|string',
        ]);

        $ip = $request->ip();
        $apName = $request->apName;
        $statusAP = $request->statusAP;

        try {
            $aruba = ArubaHelper::getClientWithLogin();

            // Lakukan request ke Aruba
            $response = $aruba['client']->get('https://' . env('IP_ARUBA') . ':4343/v1/configuration/showcommand', [
                'query' => [
                    'command' => 'reboot ap '.$apName,
                    'UIDARUBA' => $aruba['uid']
                ],
                'cookies' => $aruba['cookieJar'],
                'verify' => false,
            ]);

            LogReboot::create([
                'tgl_aksi' => Carbon::now(),
                'ip' => $ip,
                'ap_name' => $apName,
                'status_wlc' => $statusAP,
                'status_ping' => shell_exec(PHP_OS_FAMILY == 'Windows' ? "ping -n 1 $ip" : "ping -c 1 $ip"),
                'keterangan' => 'Reboot via Aruba API',
                'action_by' => $request->ip()
            ]);

            return response()->json([
                'message' => "Berhasil mengirim perintah reboot ke $apName.",
                'data' => $response,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Gagal: " . $e->getMessage()
            ], 500);
        }
    }

    public function ping(Request $request)
    {
        $ip = $request->input('ip');
        $process = shell_exec("ping $ip");

        return response()->json([
            'success' => $process,
            'error' => '',
        ]);

    }



    public function details($name)
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+details+ap-name+'.$name.'&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        // $keysToRemove = ["AP AP02-GM Rejected Virtual APs", "_data", "_meta"];
        // $data = array_diff_key($data, array_flip($keysToRemove));
        if($data == null){
            return redirect()->back()->with('info','Access Point tidak teregistrasi');
        }else{
            return view('access_point.details', compact('data'));
        }

    }

}
