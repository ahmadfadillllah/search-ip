<?php

use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TopologyController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('unit.index');
});

Route::get('/home/index', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/access_point/index', [AccessPointController::class, 'index'])->name('access_point.index');

Route::get('/client/index', [ClientController::class, 'index'])->name('client.index');
Route::get('/client/show/{name}', [ClientController::class, 'show'])->name('client.show');

Route::get('/units/index', [UnitController::class, 'index'])->name('unit.index');

Route::get('/topology/index', [TopologyController::class, 'index'])->name('topology.index');

Route::get('/settings/index', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
