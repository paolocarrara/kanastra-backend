<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFile;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    function upload(Request $request) {
        
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $basePath = 'public';
            $filename = Str::uuid();
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

        return response()->json(null, Response::HTTP_BAD_REQUEST);
    }
}
