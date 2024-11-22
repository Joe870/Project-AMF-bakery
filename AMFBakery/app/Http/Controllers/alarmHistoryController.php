<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlarmHistoryController extends Controller
{
    public function show()
    {
        $filePath = storage_path('app/public/AlarmHistory.csv');

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // define data to filter and such
        $handle = fopen($filePath, 'r');
        $data = [];
        $errorCount = 0;
        $errorFrequencies = [];
        $duplicateMessages = [];
        $chunkSize = 1000;
        $otherdata = 0; // used for row above the csv file with other data
        $totalData = $chunkSize + $otherdata;
        $messages = []; // To track all messages across rows

        // Read header row
        $header = fgetcsv($handle);
        if ($header) {
            $data[] = $header; // Store header in $data
        }

        // Loop through CSV rows
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;

            $messageColumnIndex = 1;

            if (isset($row[$messageColumnIndex]) && !empty($row[$messageColumnIndex])) {
                $message = trim($row[$messageColumnIndex]);

                // Count total occurrences of each message
                $messages[] = $message;
                $errorCount++;

                if (isset($errorFrequencies[$message])) {
                    $errorFrequencies[$message]++;
                } else {
                    $errorFrequencies[$message] = 1;
                }
            }

            // limit the program to chunksize (totalData)
            if (count($data) >= $totalData) {
                break;
            }
        }


        fclose($handle);

        // Check how often messages occured
        foreach ($errorFrequencies as $message => $count) {
            if ($count > 1) {
                $duplicateMessages[] = [
                    'message' => $message,
                    'count' => $count
                ];
            }
        }

        return view('csv.show', compact('data', 'errorCount', 'errorFrequencies', 'duplicateMessages'));
    }
}
