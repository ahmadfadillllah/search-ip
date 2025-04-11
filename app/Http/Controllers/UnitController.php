<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UnitController extends Controller
{
    //
    public function index()
    {
        $unit = DB::connection('focus')
                    ->table('FLT_VEHICLE')
                    ->select([
                        'VHC_ID',
                        'EQU_TYPEID',
                        'NET_IPADDRESS',
                        'APP_VERSION',
                    ])
                    ->where('VHC_ACTIVE', true)
                    ->get();


        return view('unit.index', compact('unit'));

    }
}
