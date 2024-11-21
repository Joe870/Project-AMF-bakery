<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get("/test", [TestController::class, "index"]);

require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);

use App\Http\Controllers\FileUploadController;

Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');

