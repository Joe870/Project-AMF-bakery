<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\alarmHistoryController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/csv', [alarmHistoryController::class, 'show']);
