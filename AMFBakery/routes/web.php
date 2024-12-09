<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardComponent;

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


Route::get('/dashboard/column-chart', function () {
    $columnChartModel = (new DashboardComponent())->getColumnChartModel();

    return view('charts.column', compact('columnChartModel'));
})->name('charts.column');


Route::get('/dashboard/line-chart', function () {
    $lineChartModel = (new DashboardComponent())->getLineChartModel();

    return view('charts.line', compact('lineChartModel'));
})->name('charts.line');

Route::get('/dashboard/pie-chart', function () {
    $pieChartModel = (new DashboardComponent())->getPieChartModel();
    return view('charts.pie', compact('pieChartModel')); 
})->name('charts.pie');
