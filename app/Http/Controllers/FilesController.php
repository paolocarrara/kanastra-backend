<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFile;
use App\Models\Boleto;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FilesController extends Controller
{
    function upload(Request $request) {
        
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $basePath = 'public';
            $filename = Str::uuid();
            $extension = $request->file('file')->extension();
            $request->file('file')->storePubliclyAs($basePath, $filename.".csv");
            $path = Storage::url($basePath . '/' . $filename. '.csv');

            $file = new File([
                'filename' => $filename,
                'extension' => 'csv',
                'path' => $path,
            ]);

            $file->save();

            ProcessFile::dispatch($file)->onQueue('process_file');

            return response()->json($file);
        }

        return response()->json("NOK");
    }

    function test(Request $request) {
        return response()->json('HI');
    }
}
