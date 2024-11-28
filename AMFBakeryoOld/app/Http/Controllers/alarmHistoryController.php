<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class alarmHistoryController extends Controller
{
    public function show()
    {
        // Path to (for now) csv file
        // note that this is only temporary, later on we will try to import from the webpage
        // instead of using the manual way
        $filePath = storage_path('app/public/AlarmHistory.csv');

        $handle = fopen($filePath, 'r');

        if (!$handle) {
            abort(404, 'File not found');
        }

        // read out the csv file
        $data = [];
        $otherdata = 7;
        $chunkSize = 1; // define amount of rows you want to be returned
        $totalData = $chunkSize + $otherdata;
        while (($row = fgetcsv($handle)) !== false) {
            // Add row to data (or process it)
            $data[] = $row;

            // Puts a limit to only loading the amount of chunk size
            if (count($data) >= $totalData) {
                break;
            }
        }

        fclose($handle);

        return view('csv.show', compact('data'));
    }
}
