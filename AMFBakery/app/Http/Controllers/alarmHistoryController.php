<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class alarmHistoryController extends Controller
{
    public function show(Request $request)
    {
        // Pad naar het CSV-bestand
        $filePath = storage_path('app/public/AlarmHistory.csv');

        // Controleer of het bestand bestaat
        if (!file_exists($filePath)) {
            return view('csv.show', ['data' => []])->with('message', 'Upload een CSV-bestand om te bekijken.');
        }

        // Open het bestand voor lezen
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            abort(500, 'CSV-bestand kan niet worden geopend.');
        }

        // Lees de inhoud van het CSV-bestand
        $data = [];
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;
        }
        fclose($handle);

        // Controleer of het bestand leeg is
        if (empty($data)) {
            return view('csv.show', ['data' => []])->with('message', 'Het CSV-bestand is leeg.');
        }

        // Headers en data scheiden
        $headers = array_shift($data);

        // Datum filtering
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        if ($start_date || $end_date) {
            $filteredData = [];
            foreach ($data as $row) {
                $rowDate = $row[0]; // Neem aan dat de datum in de eerste kolom staat
                if ($start_date && $rowDate < $start_date) {
                    continue;
                }
                if ($end_date && $rowDate > $end_date) {
                    continue;
                }
                $filteredData[] = $row;
            }
            $data = $filteredData;
        }

        // Data inclusief headers teruggeven
        array_unshift($data, $headers);
        return view('csv.show', compact('data'));
    }
}
