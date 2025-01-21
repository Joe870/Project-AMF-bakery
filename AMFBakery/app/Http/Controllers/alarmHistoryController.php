<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlarmHistoryController extends Controller
{
    public function show(Request $request)
    {
        // Pad naar het CSV-bestand
        $filePath = storage_path('app/public/AlarmHistory.csv');

        // Controleer of het bestand bestaat
        if (!file_exists($filePath)) {
            return view('csv.show', ['data' => []])
                ->with('message', 'Upload een CSV-bestand om te bekijken.');
        }

        // Open het bestand voor lezen
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            abort(500, 'CSV-bestand kan niet worden geopend.');
        }

        // Lees de inhoud van het CSV-bestand
        $data = [];
        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            // Convert encoding to UTF-8 if needed
            $row = array_map(function($cell) {
                return mb_convert_encoding($cell, 'UTF-8', 'auto');
            }, $row);
            $data[] = $row;
        }
        fclose($handle);

        // Controleer of het bestand leeg is
        if (empty($data)) {
            return view('csv.show', ['data' => []])
                ->with('message', 'Het CSV-bestand is leeg.');
        }

        // Headers en data scheiden
        $headers = array_shift($data);

        // Datum filtering
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        if ($start_date || $end_date) {
            $filteredData = [];
            foreach ($data as $row) {
                try {
                    $rowDate = \DateTime::createFromFormat('Y-m-d', $row[0]); // Adjust format as needed
                    $startDateTime = $start_date ? \DateTime::createFromFormat('Y-m-d', $start_date) : null;
                    $endDateTime = $end_date ? \DateTime::createFromFormat('Y-m-d', $end_date) : null;

                    if ($startDateTime && $rowDate < $startDateTime) {
                        continue;
                    }
                    if ($endDateTime && $rowDate > $endDateTime) {
                        continue;
                    }
                    $filteredData[] = $row;
                } catch (\Exception $e) {
                    // Skip invalid dates
                    continue;
                }
            }
            $data = $filteredData;
        }

        // Data inclusief headers teruggeven
        array_unshift($data, $headers);
        return view('csv.show', compact('data'));
    }
}