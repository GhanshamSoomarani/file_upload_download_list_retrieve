<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function fileUpload(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $originalName = $request->file('file')->getClientOriginalName();
            $nameOnly = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();

            $newFileName = time() . '_' . str_replace(' ', '_', $nameOnly) . '.' . $extension;

            try {
                $path = $request->file('file')->storeAs('public/post_img', $newFileName);

                if (!$path) {
                    return response()->json([
                        'status' => false,
                        'message' => 'File failed to store.'
                    ], 500);
                }

                $fileObj = new File([
                    'file' => $newFileName,
                    'file_path' => $path,
                    'file_url' => Storage::url($path),
                ]);

                if ($fileObj->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'File uploaded successfully',
                        'file_path' => $path,
                        'file_url' => Storage::url($path)
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to save file in DB.'
                    ], 500);
                }
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid file or no file uploaded'
        ], 400);
    }


    public function listFiles()
    {
        $files = File::all()->map(function ($file) {
            return [
                'id' => $file->id,
                'filename' => $file->file,
                'url' => Storage::url('public/post_img/' . $file->file)
            ];
        });

        return response()->json([
            'status' => true,
            'files' => $files
        ]);
    }

   public function downloadFile($id)
{
    $file = File::find($id);

    if (!$file) {
        return response()->json([
            'status' => false,
            'message' => 'File not found in database'
        ], 404);
    }

    $fullPath = Storage::path($file->file_path);
    if (!file_exists($fullPath)) {
        return response()->json([
            'status' => false,
            'message' => 'File missing on disk'
        ], 404);
    }

    return response()->download($fullPath, $file->file);
}

}
