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

    public function importCsvFromFile()
    {
        // Define the file path
        $filePath = storage_path('app/public/AlarmHistory.csv');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Open the file
        $handle = fopen($filePath, 'r');

        // Read the header
        $header = fgetcsv($handle);

        // Map the headers to your table columns (ensure the correct order)
        $columns = [
            'EventTime', 'Message', 'StateChangeType', 'AlarmClass', 'AlarmCount', 'AlarmGroup',
            'Name', 'AlarmState', 'Condition', 'CurrentValue', 'InhibitState', 'LimitValueExceeded',
            'Priority', 'Severity', 'Tag1Value', 'Tag2Value', 'Tag3Value', 'Tag4Value',
            'EventCategory', 'Quality', 'Expression'
        ];

        // Read each row in the file
        while (($row = fget2csv($handle)) !== false) {
            // Combine headers and row data
            $data = array_combine($columns, $row);

            // Insert data into the database
            AlarmHistory::create([
                'EventTime' => $data['EventTime'],
                'Message' => $data['Message'],
                'StateChangeType' => $data['StateChangeType'],
                'AlarmClass' => $data['AlarmClass'],
                'AlarmCount' => is_numeric($data['AlarmCount']) ? $data['AlarmCount'] : null, // Replace empty with NULL
                'AlarmGroup' => $data['AlarmGroup'],
                'Name' => $data['Name'],
                'AlarmState' => $data['AlarmState'],
                'Condition' => $data['Condition'],
                'CurrentValue' => $data['CurrentValue'],
                'InhibitState' => $data['InhibitState'],
                'LimitValueExceeded' => $data['LimitValueExceeded'],
                'Priority' => $data['Priority'],
                'Severity' => $data['Severity'],
                'Tag1Value' => $data['Tag1Value'],
                'Tag2Value' => $data['Tag2Value'],
                'Tag3Value' => $data['Tag3Value'],
                'Tag4Value' => $data['Tag4Value'],
                'EventCategory' => $data['EventCategory'],
                'Quality' => $data['Quality'],
                'Expression' => $data['Expression'],
            ]);
        }

        fclose($handle);

        return response()->json(['message' => 'CSV imported successfully']);
    }
}
