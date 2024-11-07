<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $unit = DB::connection('sqlsrv')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID',
                        'EQU_TYPEID',
                        'NET_IPADDRESS',
                        'APP_VERSION',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();

        return view('dashboard.index', compact('unit'));
    }
}
