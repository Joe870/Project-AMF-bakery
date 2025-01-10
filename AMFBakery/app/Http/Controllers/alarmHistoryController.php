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

    public function importCsvFromFile()
    {
        $filePath = storage_path('app/public/uploads/WIzIWRF9CFcvWmOukqiEWowrymz8JtsMrB3vrA5l.csv');

        if (!file_exists($filePath)) {
//            dd($filePath);
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

        return view('dashboard');
    }
}
