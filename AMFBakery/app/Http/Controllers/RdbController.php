<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RdbController extends Controller
{
    public function convertRdb(Request $request)
    {
        $request->validate([
            'rdb_file' => 'required|file|mimes:rdb',
        ]);

        // Save the uploaded file temporarily
        $uploadedFile = $request->file('rdb_file');
        $filePath = $uploadedFile->storeAs('temp', $uploadedFile->getClientOriginalName());

        // Define the output folder
        $outputFolder = storage_path('app/csv_output');

        // Ensure the output folder exists
        if (!file_exists($outputFolder)) {
            mkdir($outputFolder, 0777, true);
        }

        // Path to the Python script
        $pythonScript = base_path('convert_rdb_to_csv.py');

        // Run the Python script
        $process = new Process([
            'python',
            $pythonScript,
            storage_path('app/' . $filePath),
            $outputFolder,
        ]);

        $process->run();

        // Check if the script executed successfully
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Provide feedback and list generated CSV files
        $csvFiles = array_diff(scandir($outputFolder), ['.', '..']);
        return response()->json([
            'message' => 'RDB file converted successfully!',
            'csv_files' => $csvFiles,
        ]);
    }
}