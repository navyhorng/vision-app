<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleVisionService
{
    protected $endpoint;
    protected $key;

    public function __construct()
    {
        $this->key = config('services.vision.key');
        $this->endpoint = 'https://vision.googleapis.com/v1/images:annotate?key=' . $this->key;
    }

    public function scanImage(string $base64Image): array
    {
        return [
            'text' => $this->detectText($base64Image),
            'labels' => $this->detectLabels($base64Image),
            'colors' => $this->detectImageProperties($base64Image),
        ];
    }
    public function detectText(string $base64Image)
    {
        $response = Http::post($this->endpoint, [
            'requests' => [
                [
                    'image' => [
                        'content' => $base64Image,
                    ],
                    'features' => [
                        [
                            'type' => 'TEXT_DETECTION',
                        ],
                    ],
                ],
            ],
        ]);
        return response()->json([
            'success' => true,
            'data' => $response->json(),
        ]);
    }

    public function detectLabels(string $base64Image)
    {
        $response = Http::post($this->endpoint, [
            'requests' => [
                [
                    'image' => [
                        'content' => $base64Image,
                    ],
                    'features' => [
                        [
                            'type' => 'LABEL_DETECTION',
                            'maxResults' => 10,
                        ],
                    ],
                ],
            ],
        ]);
        return response()->json([
            'success' => true,
            'data' => $response->json(),
        ]);
    }

    public function detectImageProperties(string $base64Image): array
    {
        $response = Http::post($this->endpoint, [
            'requests' => [[
                'image' => [
                    'content' => $base64Image,
                ],
                'features' => [
                    ['type' => 'IMAGE_PROPERTIES'],
                ],
            ]],
        ]);

        return $response->json()['responses'][0]['imagePropertiesAnnotation']['dominantColors']['colors'] ?? [];
    }
}
