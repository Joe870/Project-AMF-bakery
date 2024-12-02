<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdbController;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');


require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);
Route::get('/hello', [Welcome::class, "hello"]);
Route::get('/rdbconversion/upload', [RdbController::class, 'upload']);
Route::post('/rdbconversion/csv_file', [RdbController::class, "convert"])->name("convert"); 