<?php

namespace App\Http\Controllers;

use App\Models\AlarmHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    // Function to directly upload data to the database. First validates headers, then uploads file to the DB.
    //Has a bug that I have yet to fix: File validation, uploading the wrong file sometimes gives the wrong error message
    // Okay suuuuper weird bug, I have a specific jpg img that somehow tells the code that it's a csv file
    // kinda confused...
public function uploadValidateCsv(Request $request)
{
    $chunkSize = 500; // Process the file in chunks of 500 rows at a time

        $requiredHeaders = [
            'EventTime', 'Message', 'StateChangeType', 'AlarmClass', 'AlarmCount',
            'AlarmGroup', 'Name', 'AlarmState', 'Condition', 'CurrentValue', 'InhibitState',
            'LimitValueExceeded', 'Priority', 'Severity', 'Tag1Value', 'Tag2Value',
            'Tag3Value', 'Tag4Value', 'EventCategory', 'Quality', 'Expression'
        ];

        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Try catch to catch any problems occurring while uploading the file.
        try {
            $file = $request->file('csv_file');
            $filePath = $file->getRealPath();
            $handle = fopen($filePath, 'r');
            $rowChunk = []; // Store rows in chunks
            //Skip all lines that start with #, then start the headers where row ended.
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (strpos($row[0], '#') === 0) {
                    continue;
                }
                $headers = $row;
                break;
            }
            // Check if headers of given file are 1:1 with the expected headers.
            if (!$headers || array_diff($requiredHeaders, $headers)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with([
                        'expected_headers' => $requiredHeaders,
                        'actual_headers' => $headers ?? [],
                    ])
                    ->withErrors([
                        'csv_file' => 'The uploaded file does not have the required headers. Please verify the file.',
                    ]);
            }
            // Use the second argument in the parameter to change the amount of files you want to upload to the DB.
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data = array_combine($headers, $row);
                if (array_diff_key(array_flip($requiredHeaders), $data)) {
                    continue;
                }
                $rowChunk[] = [
                    'EventTime' => $data['EventTime'],
                    'Message' => $data['Message'],
                    'StateChangeType' => $data['StateChangeType'],
                    'AlarmClass' => $data['AlarmClass'],
                    'AlarmCount' => is_numeric($data['AlarmCount']) ? $data['AlarmCount'] : null,
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
                ];
                if (count($rowChunk) >= $chunkSize) {
                    AlarmHistory::insert($rowChunk);
                    $rowChunk = [];
                }

                // Removed individual AlarmHistory::create() calls
                // Bulk insert logic added within $rowChunk management

            }

            if (!empty($rowChunk)) {
                AlarmHistory::insert($rowChunk);
            }
            fclose($handle);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'csv_file' => 'An error occurred while processing the CSV file: ' . $e->getMessage(),
            ]);
        }
    }

}
