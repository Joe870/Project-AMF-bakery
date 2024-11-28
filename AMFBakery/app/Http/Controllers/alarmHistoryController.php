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

        $handle = fopen($filePath, 'r');
        $data = [];
        $errorCount = 0;
        $errorFrequencies = [];
        $duplicateMessages = [];
        $chunkSize = 1000;
        $otherData = 6;
        $chunkSize += $otherData;
        $messages = []; // To track all messages across rows

        // Read header row
        $header = fgetcsv($handle);
        if ($header) {
            $data[] = $header; // Store header in $data
        }

        // Loop through CSV rows
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;

            // Assume message (error details) is in a specific column (index 1 for 'Message')
            $messageColumnIndex = 1; // Adjust based on your CSV structure (Message column)

            if (isset($row[$messageColumnIndex]) && !empty($row[$messageColumnIndex])) {
                $message = trim($row[$messageColumnIndex]);

                // Count total occurrences of each message
                $messages[] = $message;
                $errorCount++;

                // Count the frequency of each message
                if (isset($errorFrequencies[$message])) {
                    $errorFrequencies[$message]++;
                } else {
                    $errorFrequencies[$message] = 1;
                }
                if (count($data) >= $chunkSize) {
                    break;
                }
            }
        }

        fclose($handle);

        // Identify duplicate messages (messages that appear more than once)
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
