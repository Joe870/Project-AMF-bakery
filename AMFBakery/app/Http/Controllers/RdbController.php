<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertRDBToCSV;
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
                'rdb_file' => 'required|file|',
            ]);

            // Store the uploaded file
            $path = $request->file('rdb_file')->store('uploads', 'public');

            // Get the full path of the file
            $fullPath = storage_path('app/public/uploads' . $path);

            // Define output directory for CSV files
            $outputDir = storage_path('app/public/csv_outputs');
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


    public function uploadFile(Request $request)
    {

        $request->validate([
            'rdb_file' => 'required|file',
        ]);
        if ($request->file('rdb_file')->isValid()){
            $filePath = $request->file('rdb_file')->store('uploads', 'public');
            return back()->with('success', 'File uploaded succesfully')->with('file', $filePath);
        }
        return back()->with('error', 'File upload failed');
    }

    public function showFiles()
    {
        $files = Storage::files('public/csv_outputs');

        return view("rdbconversion.upload", ['files' => $files]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'rdb_file' => 'required|file|mimes:rdb|max:2048',
        ]);

        $file = $request->file('rdb_file');

        $filePath = $file->store('uploads');

        ConvertRDBToCSV::dispatch(storage_path("app/{$filePath}"));

        return redirect()->route('files.list')->with('message', 'File uploaded and conversion started!');
    }
}
