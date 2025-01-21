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

        if ($request->file('csv_file')->isValid()) {
            $file = $request->file('csv_file');
            $file->storeAs('public', 'AlarmHistory.csv');
            return redirect()->route('csv.show')->with('success', 'CSV-bestand succesvol geüpload!');
        }

        return redirect()->back()->with('error', 'Er is een fout opgetreden bij het uploaden.');
    }
}
