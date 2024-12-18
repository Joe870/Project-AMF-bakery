<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ConvertRDBToCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public function __construct($filepath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $process = new Process([
            'python3', base_path('rdbconversion/convert_rdb.py'), $this->filePath
        ]);

        $process->run();

        if (!$process->isSuccesful()) {
            throw new ProcessFailedException($process);
        }

        \Log::info('File converted succesfully:', [
            'file' => $this->filePath,
            'output' => $process->getOutput()
        ]);
    }
}
