<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MonitorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/terminal', function () {
    return view('terminal');
});

Route::get('/gamble', function () {
    return view('gamble');
});

Route::get('/monitor', [MonitorController::class, 'index']);
Route::get('/api/stats', [MonitorController::class, 'stats']);
