<?php

use App\Http\Controllers\alarmHistoryController;
use Illuminate\Support\Facades\Route;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->name('profile');


require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);

//Route::post('/alarm-history/import', [AlarmHistoryController::class, 'importCsv'])->name('alarm-history.import');
Route::get('/alarm-history/import-from-file', [AlarmHistoryController::class, 'importCsvFromFile'])->name('alarm-history.import-file');

