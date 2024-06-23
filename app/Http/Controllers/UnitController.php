<?php

namespace App\Http\Controllers;

use App\Models\Session;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Cookie\CookieJar;

class UnitController extends Controller
{
    //
    public function index()
    {

        //Get UIDAruba
        $client = new Client();
        $clientt = new Client();

        try {
            $response = $client->request('GET', 'https://10.10.2.12:4343/v1/api/login', [
                'query' => [
                    'username' => env('USERNAME_ARUBA'),
                    'password' => env('PASSWORD_ARUBA')
                ],
                'verify' => false
            ]);


            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

        } catch (GuzzleHttp\Exception\RequestException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();
        }

        $resultObject = json_decode($body);
        $uidAruba = $resultObject->_global_result->UIDARUBA;


        $cookieJar = CookieJar::fromArray([
            'UIDARUBA' => $uidAruba
        ], 'https://10.10.2.12:4343/v1/configuration/showcommand?command=show+user-table');

        $results = $client->request('GET', '/units/index', ['cookies' => $cookieJar]);

        dd($results);

    }
}
