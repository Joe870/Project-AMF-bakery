<?php

use App\Http\Controllers\alarmHistoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard' )
    ->name('dashboar');

Route::view('profile', 'profile')
    ->name('profile');


require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);
