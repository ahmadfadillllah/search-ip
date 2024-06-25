<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UnitController extends Controller
{
    //
    public function index()
    {
        $unit = Unit::all();

        return view('unit.index', compact('unit'));

    }
}
