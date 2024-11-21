<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Valideer de binnenkomende bestanden
        $request->validate([
            'files.*' => 'required|file|max:2048', // Maximaal 2MB per bestand
        ]);

        $uploadedFiles = []; // Array om opgeslagen paden op te slaan

        // Controleer of er bestanden zijn geüpload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Sla het bestand op in de 'public/uploads' map
                $path = $file->store('uploads', 'public');
                $uploadedFiles[] = $path; // Voeg het opgeslagen pad toe aan de array
            }
        }

        // Stuur een JSON-reactie terug met de opgeslagen bestanden
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
        ]);
    }
}
