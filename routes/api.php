<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TopologyController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/client/api', [ClientController::class, 'api'])->name('client.api');

Route::get('/topology/api', [TopologyController::class, 'api'])->name('topology.api');

Route::get('/testing/api', [TestingController::class, 'api'])->name('testing.api');

Route::get('/maps/api', [MapsController::class, 'api'])->name('maps.api');


