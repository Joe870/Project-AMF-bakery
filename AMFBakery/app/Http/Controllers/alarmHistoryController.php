<?php

namespace App\Http\Controllers;

use App\Models\AlarmHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class alarmHistoryController extends Controller
{
    public function show()
    {
        $filePath = storage_path('app/public/AlarmHistory.csv');

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle); // Read the header row

        if (!$header) {
            fclose($handle);
            abort(400, 'CSV file is empty or invalid.');
        }

        Log::info("CSV Header:", $header); // Log the header row

        // Map header columns to your database columns
        $columns = [
            'EventTime' => 'event_time',
            'Message' => 'message',
            'StateChangeType' => 'state_change_type',
            'AlarmClass' => 'alarm_class',
            'AlarmCount' => 'alarm_count',
            'AlarmGroup' => 'alarm_group',
            'Name' => 'name',
            'AlarmState' => 'alarm_state',
            'Condition' => 'condition',
            'CurrentValue' => 'current_value',
            'InhibitState' => 'inhibit_state',
            'LimitValueExceeded' => 'limit_value_exceeded',
            'Priority' => 'priority',
            'Severity' => 'severity',
            'Tag1Value' => 'tag1_value',
            'Tag2Value' => 'tag2_value',
            'Tag3Value' => 'tag3_value',
            'Tag4Value' => 'tag4_value',
            'EventCategory' => 'event_category',
            'Quality' => 'quality',
            'Expression' => 'expression',
        ];

        // Process CSV rows
        $data = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rowData = [];
            foreach ($header as $index => $column) {
                $dbColumn = $columns[$column] ?? null;
                if ($dbColumn) {
                    $rowData[$dbColumn] = $row[$index] ?? null;
                }
            }

            if (!empty($rowData)) {
                Log::info("Row data:", $rowData); // Log each row's data
                $data[] = $rowData;
            }
        }

        fclose($handle);

        // Log the data that will be inserted
        Log::info("Data to be inserted:", $data);

        // Insert data into the database
        if (count($data) > 0) {
            AlarmHistory::insert($data);
            Log::info("Data inserted successfully!");
        } else {
            Log::warning("No data to insert.");
        }

        return response()->json(['message' => 'CSV data saved to the database successfully!'], 200);
    }

//    public function show()
//    {
//        $filePath = storage_path('app/public/AlarmHistory.csv');
//
//        if (!file_exists($filePath)) {
//            abort(404, 'File not found');
//        }
//
//        $handle = fopen($filePath, 'r');
//        $data = [];
//        $errorCount = 0;
//        $errorFrequencies = [];
//        $duplicateMessages = [];
//        $chunkSize = 1000;
//        $otherData = 6;
//        $chunkSize += $otherData;
//        $messages = []; // To track all messages across rows
//
//        // Read header row
//        $header = fgetcsv($handle);
//        if ($header) {
//            $data[] = $header; // Store header in $data
//        }
//
//        // Loop through CSV rows
//        while (($row = fgetcsv($handle)) !== false) {
//            $data[] = $row;
//
//            // Assume message (error details) is in a specific column (index 1 for 'Message')
//            $messageColumnIndex = 1; // Adjust based on your CSV structure (Message column)
//
//            if (isset($row[$messageColumnIndex]) && !empty($row[$messageColumnIndex])) {
//                $message = trim($row[$messageColumnIndex]);
//
//                // Count total occurrences of each message
//                $messages[] = $message;
//                $errorCount++;
//
//                // Count the frequency of each message
//                if (isset($errorFrequencies[$message])) {
//                    $errorFrequencies[$message]++;
//                } else {
//                    $errorFrequencies[$message] = 1;
//                }
//                if (count($data) >= $chunkSize) {
//                    break;
//                }
//            }
//        }
//
//        fclose($handle);
//
//        // Identify duplicate messages (messages that appear more than once)
//        foreach ($errorFrequencies as $message => $count) {
//            if ($count > 1) {
//                $duplicateMessages[] = [
//                    'message' => $message,
//                    'count' => $count
//                ];
//            }
//        }
//
//        return view('csv.show', compact('data', 'errorCount', 'errorFrequencies', 'duplicateMessages'));
//    }
}
