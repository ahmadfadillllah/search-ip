<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function index()
    {
        $session = Session::first();
        return view('settings.index', compact('session'));
    }

    public function update(Request $request)
    {
        try {
            Session::where('id', 1)->update(['session' => $request->session]);

            return redirect()->back()->with('success', 'Session berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'Session gagal diupdate');
        }
    }
}
