<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageOcrJob;
use App\Models\OcrResult;
use App\Models\ScanRequest;
use App\Services\GoogleVisionService;
use Illuminate\Http\Request;

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
        $scanRequest = ScanRequest::create([
            'image_path' => $path,
            'status'     => 'pending',
        ]);
        // 3. dispatch job
        ProcessImageOcrJob::dispatch($scanRequest->id);

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $scanRequest->id,
                'status'  => $scanRequest->status,
                'image'   => asset('storage/' . $path),
            ],
        ]);
    }

    public function result(int $id)
    {
        $scanRequest = ScanRequest::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $scanRequest->id,
                'status' => $scanRequest->status,
                'text' => $scanRequest->scanResult?->raw_text,
                'image_path' => $scanRequest->image_path,
                'ocr_date' => $scanRequest->scanResult?->processed_at,
            ],
        ]);
    }
}
