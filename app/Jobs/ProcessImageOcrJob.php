<?php

namespace App\Jobs;

use App\Models\ScanRequest;
use App\Services\GoogleVisionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImageOcrJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(public int $scanRequestId) {}

    public function handle()
    {
        try {
            $scanRequest = ScanRequest::find($this->scanRequestId);

            if (!$scanRequest) return;

            $scanRequest->update([
                'status' => 'processing'
            ]);

            $imagePath = storage_path('app/public/' . $scanRequest->image_path);
            if(!file_exists($imagePath)) {
                throw new \Exception('Image file not found: ' . $imagePath);
            }

            $base64Image = base64_encode(file_get_contents($imagePath));

            $visionService = new GoogleVisionService();

            $result = $visionService->scanImage($base64Image);

            $scanRequest->scanResult()->create([
                'raw_text' => json_encode($result),
                'processed_at' => now()->toDateTimeString(),
            ]);

            $scanRequest->update([
                'status' => 'done',
            ]);

        } catch (\Exception $e) {

            $scanRequest->update([
                'status' => 'failed',
            ]);

            Log::error('Vision AI Job Failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
