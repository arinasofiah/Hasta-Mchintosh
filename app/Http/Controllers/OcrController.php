<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrController extends Controller
{
    public function ic(Request $request)
    {
        $path = $request->file('file')->store('temp');
        $text = (new TesseractOCR(storage_path("app/$path")))->run();

        preg_match('/\d{6}-\d{2}-\d{4}/', $text, $ic);
        preg_match('/Name[:\s]+([A-Z ]+)/i', $text, $name);

        return response()->json([
            'ic' => $ic[0] ?? '',
            'name' => $name[1] ?? ''
        ]);
    }

    public function license(Request $request)
    {
        $path = $request->file('file')->store('temp');
        $text = (new TesseractOCR(storage_path("app/$path")))->run();

        preg_match('/[A-Z]\d{7,}/', $text, $license);

        return response()->json([
            'license' => $license[0] ?? ''
        ]);
    }

    public function student(Request $request)
    {
        $path = $request->file('file')->store('temp');
        $text = (new TesseractOCR(storage_path("app/$path")))->run();

        preg_match('/STU\d+/', $text, $student);

        return response()->json([
            'student' => $student[0] ?? ''
        ]);
    }
}
