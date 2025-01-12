<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdbController;
use App\Livewire\DashboardComponent;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');


require __DIR__.'/auth.php';

Route::get('/csv', [alarmHistoryController::class, 'show']);
Route::get('/alarm-history/import-from-file', [AlarmHistoryController::class, 'importCsvFromFile'])->name('alarm-history.import-file');


Route::get('/', [RdbController::class, 'showFiles'])->name('files.list');
Route::post('/rdbconversion/csv_file', [RdbController::class, "convert"])->name("convert");
Route::post('rdbconversion/upload', [RdbController::class, 'uploadFile'])->name('upload.file');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

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


require __DIR__.'/auth.php';
