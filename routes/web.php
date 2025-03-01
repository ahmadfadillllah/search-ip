<?php

use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PeriodicRealtimeController;
use App\Http\Controllers\RealtimeRitationController;
use App\Http\Controllers\RitationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TopologyController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/home/index', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/access_point/index', [AccessPointController::class, 'index'])->name('access_point.index');

Route::get('/access_point/details/{name}', [AccessPointController::class, 'details'])->name('access_point.details');

Route::get('/client/index', [ClientController::class, 'index'])->name('client.index');
Route::get('/client/show/{name}', [ClientController::class, 'show'])->name('client.show');

Route::get('/units/index', [UnitController::class, 'index'])->name('unit.index');
Route::get('/units/show', [UnitController::class, 'show'])->name('unit.show');

Route::get('/topology/index', [TopologyController::class, 'index'])->name('topology.index');

Route::get('/ritation/index', [RitationController::class, 'index'])->name('ritation.index');
Route::get('/ritation/index/time', [RitationController::class, 'time'])->name('ritation.time');

Route::get('/maps/index', [MapsController::class, 'index'])->name('maps.index');

Route::get('/marker/index', [MarkerController::class, 'index'])->name('marker.index');

Route::get('/connection/index', [ConnectionController::class, 'index'])->name('connection.index');

Route::get('/periodic-realtime/index', [PeriodicRealtimeController::class, 'index'])->name('periodicrealtime.index');
Route::get('/periodic-realtime/index/notRealtime/{startDate}/{endDate}/{vhcId}', [PeriodicRealtimeController::class, 'notRealtime'])->name('periodicrealtime.notRealtime');
Route::get('/periodic-realtime/allMaps/{startDate}/{endDate}', [PeriodicRealtimeController::class, 'allMaps'])->name('periodicrealtime.allMaps');
Route::get('/periodic-realtime/mapsUnit/{startDate}/{endDate}/{vhcId}', [PeriodicRealtimeController::class, 'mapsUnit'])->name('periodicrealtime.mapsUnit');

Route::get('/realtime-ritation/index', [RealtimeRitationController::class, 'index'])->name('realtimeritation.index');
Route::get('/realtime-ritation/notrealtime/{date}/{time}', [RealtimeRitationController::class, 'notrealtime'])->name('realtimeritation.notrealtime');

Route::get('/settings/index', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

// Route::get('/ping-server/{ip}', function ($ipname) {
//     $ip = $ipname;
//     $process = Process::fromShellCommandline("ping $ip");
//     $process->run();

//     return response()->json([
//         'output' => $process->getOutput(),
//         'error' => $process->getErrorOutput(),
//     ]);
// })->name('ping');
