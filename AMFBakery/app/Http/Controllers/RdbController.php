<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RdbController extends Controller
{
    public function convert(Request $request)
    {
        {
            // Validate the file upload
            $request->validate([
                'rdb_file' => 'required|file|mimes:rdb',
            ]);
    
            // Store the uploaded file
            $path = $request->file('rdb_file')->store('rdb_files');
    
            // Get the full path of the file
            $fullPath = storage_path('app/' . $path);
    
            // Define output directory for CSV files
            $outputDir = storage_path('app/csv_outputs');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
    
            // Run the Python script
            $process = new Process(['python3', base_path('convert_rdb.py'), $fullPath, $outputDir]);
            $process->run();
    
            // Check for errors
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
    
            // Return the list of generated CSV files
            $csvFiles = array_diff(scandir($outputDir), ['.', '..']);
            return view('csv_file', ['files' => $csvFiles, 'outputDir' => $outputDir]);
            if (!$process->isSuccessful()) {
                dd($process->getErrorOutput());
            }
        }
    }

    public function upload(Request $request)
    {
        return view("rdbconversion.upload");
    }
}