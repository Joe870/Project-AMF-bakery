<?php

use App\Http\Controllers\alarmHistoryController;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdbController;
use App\Livewire\DashboardComponent;
use App\Livewire\LineChart;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\DatabaseController;

Route::redirect('/', '/dashboard')->name('root-redirect');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::post('/validate-upload-csv', [AlarmHistoryController::class, 'uploadValidateCsv'])->name('validate.upload.csv');

//Route::get('/csv', [alarmHistoryController::class, 'show']);
//Route::get('/alarm-history/import-from-file', [AlarmHistoryController::class, 'importCsvFromFile'])->name('alarm-history.import-file');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/upload', [RdbController::class, 'showFiles'])->name('files.list');

    Route::prefix('dashboard')->group(function () {
        Route::get('/column-chart', function () {
            $dashboardComponent = new DashboardComponent();
            $columnChartModel = $dashboardComponent->getColumnChartModel(false); // Pass 'false' to disable limiting
            return view('charts.column', compact('columnChartModel'));
        })->name('charts.column');

        Route::get('/privacy-policy', function () {
            return view('privacy-policy');
        })->name('privacy.policy');

//        Route::get('/dashboard/column-chart', function () {
//            $columnChartModel = (new DashboardComponent())->getColumnChartModel();
//
//            return view('charts.column', compact('columnChartModel'));
//        })->name('charts.column');

        Route::get('/line-chart', function () {
            $dashboardComponent = new DashboardComponent();
            $lineChartModel = $dashboardComponent->getLineChartModel(false); // Pass 'false' to disable limiting
            return view('charts.line', compact('lineChartModel'));
        })->name('charts.line');

        Route::get('/pie-chart', function () {
            $dashboardComponent = new DashboardComponent();
            $pieChartModel = $dashboardComponent->getPieChartModel(false); // Pass 'false' to disable limiting
            return view('charts.pie', compact('pieChartModel'));
        })->name('charts.pie');

        Route::view('/columnchart', 'column')->name('dashboard.columnchart');
    });
});

Route::view('/chart' ,'chart')->name('chart-view');
//Route::post('rdbconversion/upload', [RdbController::class, 'uploadFile'])->name('upload.file');

Route::get('/dashboard/all-errors', function () {
    $errors = (new DashboardComponent())->allError();

    return view('charts.error', compact('errors'));
})->name('charts.error');

Route::post('/clear-database', [DatabaseController::class, 'clearDatabase'])->name('clear.database');
