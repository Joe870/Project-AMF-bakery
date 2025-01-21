<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdbController;
use App\Livewire\DashboardComponent;
use App\Livewire\LineChart;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\DatabaseController;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::post('/validate-upload-csv', [AlarmHistoryController::class, 'uploadValidateCsv'])->name('validate.upload.csv');

Route::get('/csv', [alarmHistoryController::class, 'show']);
Route::get('/alarm-history/import-from-file', [AlarmHistoryController::class, 'importCsvFromFile'])->name('alarm-history.import-file');

Route::get('/hello', [Welcome::class, "hello"]);

Route::get('/rdbconversion/upload', [RdbController::class, 'uploadFile'])->name("upload");
Route::get('/rdbconversion/upload', [RdbController::class, 'uploadFile'])->name("DisplayUpload");
Route::get('/', [RdbController::class, 'showFiles'])->name('files.list');
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

Route::view('/chart' ,'chart')->name('chart-view');

Route::view('/dashboard/columnchart', 'column')->name('dashboard.columnchart');
Route::post('rdbconversion/upload', [RdbController::class, 'uploadFile'])->name('upload.file');

Route::get('/dashboard/all-errors', function () {
    $errors = (new DashboardComponent())->allError();

    return view('charts.error', compact('errors'));
})->name('charts.error');

Route::post('/clear-database', [DatabaseController::class, 'clearDatabase'])->name('clear.database');