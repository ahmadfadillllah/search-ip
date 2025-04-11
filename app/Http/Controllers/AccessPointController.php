<?php

namespace App\Http\Controllers;

use App\Models\LogReboot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Symfony\Component\Process\Process;

class AccessPointController extends Controller
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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=show+ap+database+long&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        $data = collect($data['AP Database'])->sortBy('Status');

        return view('access_point.index', compact('data'));

    }

    public function reboot(Request $request)
    {
        $ip = $request->input('ip');
        $apname = $request->input('apName');
        $statusap = $request->input('statusAP');

        $lastId = LogReboot::max('id') ?? 1;
        $newId = $lastId + 1;

        LogReboot::create([
            'id' => $newId,
            'tgl_aksi' => Carbon::now(),
            'ip' => $ip,
            'ap_name' => $apname,
            'status_wlc' => $statusap,
            'status_ping' => shell_exec("ping $ip"),
            'keterangan' => 'Reboot AP',
            'action_by' => request()->ip()
        ]);


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

        $response = $client->get('https://10.10.2.12:4343/v1/configuration/showcommand?command=apboot+ap-name+'.$apname.'&UIDARUBA='.$firstCookie['Value'], [
            'cookies' => $cookieJar,
            'verify' => false,
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        $min = rand(2, 10);
        $max = rand($min + 1, 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Reboot ' . $apname . ' (' . $ip . ') berhasil, harap menunggu sekitar '.$min.'-'.$max.' menit'
        ]);
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
