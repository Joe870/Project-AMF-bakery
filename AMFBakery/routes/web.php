<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Homepagina
Route::view('/', 'welcome');

// Dashboard (beschikbaar na authenticatie en verificatie)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profielpagina (alleen toegankelijk voor ingelogde gebruikers)
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Test route (voorbeeld controller)
Route::get("/test", [TestController::class, "index"]);

// CSV-weergave route
Route::get('/csv', [alarmHistoryController::class, 'show'])->name('csv.show');

// Bestand uploaden via formulier
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');

// Routes voor authenticatie (login, registratie, wachtwoordherstel, enz.)
require __DIR__.'/auth.php';
