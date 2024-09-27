<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    //
    public function index()
    {
        $marker = Marker::all();
        return view('marker.index', compact('marker'));
    }
}
