<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class alarmHistoryController extends Controller
{
    public function show()
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

        // Stuur de data naar de view
        return view('csv.show', compact('data'));
    }
}
