<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageOcrJob;
use App\Models\OcrResult;
use Illuminate\Http\Request;
use App\Services\GoogleVisionService;

class ProductScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $image = $request->file('image');
        $base64Image = base64_encode(file_get_contents($image->getRealPath()));

        $visionService = new GoogleVisionService();
        $result = $visionService->scanImage($base64Image);
        return response()->json($result);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        // 1. store image
        $path = $request->file('image')->store('ocr', 'public');

        // 2. create DB record
        $ocr = OcrResult::create([
            'image_path' => $path,
            'status' => 'pending',
        ]);

        // 3. dispatch job
        ProcessImageOcrJob::dispatch($ocr->id);

        return response()->json([
            'id' => $ocr->id,
            'status' => 'pending',
        ]);
    }

    public function result(int $id)
    {
        $ocr = OcrResult::findOrFail($id);

        return response()->json([
            'id' => $ocr->id,
            'status' => $ocr->status,
            'text' => $ocr->text,
            'image_path' => $ocr->image_path,
            'ocr_date' => $ocr->ocr_date,
        ]);
    }
}
