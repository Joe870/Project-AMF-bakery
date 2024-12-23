<?php

namespace App\Http\Controllers;

use App\Models\AlarmHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class alarmHistoryController extends Controller
{
    public function show() // fine to later remove this function. Currently keeping it for testing purposes. Will be using the dashboard and importCsvFromFile function in the future.
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
        $messages = [];

        // Read header row
        $header = fgetcsv($handle);
        if ($header) {
            $data[] = $header;
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

        // check for duplicate messages (messages that appear more than once)
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
        $filePath = storage_path('app/public/AlarmHistory.csv');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $handle = fopen($filePath, 'r');

        $header = null;
        while (($row = fgetcsv($handle)) !== false) {
            // Skip lines starting with #
            if (strpos($row[0], '#') === 0) {
                continue;
            }

            // If header has not been set, assign and skip to next iteration
            if (!$header) {
                $header = $row;
                continue;
            }

            // Map the data to columns
            $data = array_combine($header, $row);

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
