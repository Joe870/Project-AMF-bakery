<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validatie van het bestand
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Sla het geüploade bestand op in de `storage/app/public`-map
        $path = $request->file('csv_file')->storeAs('public', 'AlarmHistory.csv');

        // Redirect terug naar de CSV-weergavepagina
        return redirect()->route('csv.show')->with('message', 'CSV-bestand succesvol geüpload!');
    }
}
