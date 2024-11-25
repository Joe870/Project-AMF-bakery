<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdbController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);
Route::get('/hello', [Welcome::class, "hello"]);
Route::post('/convert-rdb', [RdbController::class, 'convertRdb'])->name('convert.rdb');