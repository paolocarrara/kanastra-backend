<?php

namespace App\Jobs;

use App\Models\Boleto;
use App\Models\File;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct(public File $file2)
    {
        $this->file = $file2;
        $this->onQueue('process_file');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = $this->readCSV($this->file->path);

        foreach ($result as $item) {
            $boleto = new Boleto([
                'name' => $item[0],
                'document' => $item[1],
                'email' => $item[2],
                'value' => $item[3],
                'dueDate' => $item[4],
                'externalId' => $item[5],
            ]);

            $boleto->save();

            ProcessBoleto::dispatch($boleto)->onQueue('process_boleto');
        }
    }

    public function readCSV($path, $delimiter = ',') {
        $line_of_text = [];

        try {
            $url = 'http://backend:8000' . $path;
            $fileHandle = fopen($url, 'r');
        
            while ($csvRow = fgetcsv($fileHandle, null, $delimiter)) {
                $line_of_text[] = $csvRow;
            }

            fclose($fileHandle);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
       
        
        return $line_of_text;
    }
}
