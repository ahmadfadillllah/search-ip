<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    //

    public function index()
    {
        return view('maps.index');
    }

    public function api()
    {
        $markers = Marker::all();

        // Mengambil koneksi antar marker
        $connections = [];
        foreach ($markers as $marker) {
            // Pastikan marker memiliki koneksi
            if ($marker->connections->isNotEmpty()) {
                foreach ($marker->connections as $connectedMarker) {
                    $connections[] = [
                        'from' => $marker->id,
                        'to' => $connectedMarker->id,
                    ];
                }
            }
        }

        // Mengembalikan respons JSON
        return response()->json([
            'markers' => $markers,
            'connections' => $connections,
        ]);
    }
}
