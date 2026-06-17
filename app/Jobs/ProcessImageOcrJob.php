<?php

namespace App\Jobs;

use App\Models\OcrResult;
use App\Services\GoogleVisionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class ProcessImageOcrJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(public int $ocrId) {}

    public function handle()
    {
        $ocr = OcrResult::find($this->ocrId);

        if (!$ocr) return;

        $ocr->update([
            'status' => 'processing'
        ]);

        try {
            $imagePath = storage_path('app/public/' . $ocr->image_path);

            $base64Image = base64_encode(file_get_contents($imagePath));

            $visionService = new GoogleVisionService();

            $result = $visionService->scanImage($base64Image);

            $ocr->update([
                'text' => $result,
                'status' => 'success',
                'ocr_date' => now(),
            ]);

        } catch (\Exception $e) {

            $ocr->update([
                'status' => 'failed',
                'text' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
