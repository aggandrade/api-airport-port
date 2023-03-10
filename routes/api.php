<?php

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

Route::get('/ports', [\App\Http\Controllers\Ports\PortsController::class, 'index'])->name('ports.index');

Route::get('/ports/{id}', [\App\Http\Controllers\Ports\PortsController::class, 'show'])->name('ports.unique');

Route::post('/ports/redirect', [\App\Http\Controllers\Ports\PortsController::class, 'redirect'])->name('ports.redirect');

Route::get('/airports', [\App\Http\Controllers\Airports\AirportsController::class, 'index'])->name('airports.index');

Route::get('/airports/{id}', [\App\Http\Controllers\Airports\AirportsController::class, 'show'])->name('airports.unique');
