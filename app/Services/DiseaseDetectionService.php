<?php

namespace App\Services;

use App\Models\DiseaseDetection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DiseaseDetectionService
{
    protected string $apiKey;
    protected string $model;
    protected string $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = (string) (config('services.anthropic.key') ?? env('ANTHROPIC_API_KEY') ?? '');
        $this->model  = (string) (config('services.anthropic.model') ?? 'claude-sonnet-4-6');
    }

    /**
     * Analyse an uploaded image and return a structured disease detection result.
     */
    public function analyse(DiseaseDetection $detection): DiseaseDetection
    {
        if (empty($this->apiKey)) {
            return $this->fallbackAnalysis($detection, 'ANTHROPIC_API_KEY not configured.');
        }

        try {
            $imagePath = storage_path('app/public/' . $detection->image_path);
            if (!file_exists($imagePath)) {
                return $this->fallbackAnalysis($detection, 'Image file not found on disk.');
            }

            $imageData   = base64_encode(file_get_contents($imagePath));
            $mimeType    = mime_content_type($imagePath);
            $animalType  = $detection->animal_type ?? 'livestock';
            $symptoms    = $detection->symptoms_reported ?? 'No symptoms reported by the user.';

            $prompt = <<<PROMPT
You are a highly trained veterinary AI assistant specialised in diagnosing livestock diseases from photographic evidence. You have expertise equivalent to a board-certified large-animal veterinarian with 20+ years of experience across cattle, goat, sheep, pig, poultry, and other common farm animals.

Carefully examine the provided image of a {$animalType}.

User-reported symptoms / observations: {$symptoms}

Your task:
1. Identify ALL visible signs of disease, injury, malnutrition, or abnormality in the image.
2. List the most likely diagnoses (up to 5) with probability percentages that sum to 100%.
3. Assess urgency (low / medium / high / critical).
4. Provide clear, actionable recommendations for the farmer.
5. State your overall confidence in this assessment.

Respond ONLY with valid JSON in exactly this structure:
{
  "is_sick": true or false,
  "confidence_score": <number 0-100>,
  "urgency_level": "low" | "medium" | "high" | "critical",
  "summary": "<2-3 sentence plain-language summary>",
  "detected_conditions": [
    {
      "name": "<disease or condition name>",
      "probability": <0-100>,
      "severity": "mild" | "moderate" | "severe",
      "description": "<brief description>"
    }
  ],
  "visible_symptoms": ["<symptom 1>", "<symptom 2>"],
  "recommendations": [
    "<actionable step 1>",
    "<actionable step 2>",
    "<actionable step 3>"
  ],
  "requires_vet": true or false,
  "isolation_recommended": true or false
}
PROMPT;

            $response = Http::timeout(60)->withHeaders([
                'x-api-key'         => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post($this->apiUrl, [
                'model'      => $this->model,
                'max_tokens' => 1024,
                'messages'   => [
                    [
                        'role'    => 'user',
                        'content' => [
                            [
                                'type'   => 'image',
                                'source' => [
                                    'type'       => 'base64',
                                    'media_type' => $mimeType,
                                    'data'       => $imageData,
                                ],
                            ],
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
            ]);

            if (!$response->successful()) {
                Log::error('DiseaseDetection API error', ['status' => $response->status(), 'body' => $response->body()]);
                return $this->fallbackAnalysis($detection, 'API returned error: ' . $response->status());
            }

            $rawText = $response->json('content.0.text', '');
            $result  = $this->parseJson($rawText);

            if (!$result) {
                return $this->fallbackAnalysis($detection, 'Could not parse AI response as JSON.');
            }

            $detection->update([
                'status'              => 'completed',
                'is_sick'             => (bool) ($result['is_sick'] ?? false),
                'confidence_score'    => min(100, max(0, (float) ($result['confidence_score'] ?? 75))),
                'urgency_level'       => $result['urgency_level'] ?? 'low',
                'analysis_result'     => $result['summary'] ?? $rawText,
                'detected_conditions' => $result['detected_conditions'] ?? [],
                'recommendations'     => implode("\n", $result['recommendations'] ?? []),
                'ai_model'            => $this->model,
            ]);

        } catch (\Throwable $e) {
            Log::error('DiseaseDetection exception', ['message' => $e->getMessage()]);
            return $this->fallbackAnalysis($detection, $e->getMessage());
        }

        return $detection->fresh();
    }

    protected function parseJson(string $text): ?array
    {
        // Strip markdown code fences if present
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/', '', $text);

        $decoded = json_decode($text, true);
        return is_array($decoded) ? $decoded : null;
    }

    protected function fallbackAnalysis(DiseaseDetection $detection, string $reason): DiseaseDetection
    {
        Log::warning('DiseaseDetection fallback triggered', ['reason' => $reason]);

        $detection->update([
            'status'              => 'failed',
            'analysis_result'     => 'Automated analysis is temporarily unavailable. Please consult a veterinarian directly. (' . $reason . ')',
            'confidence_score'    => 0,
            'urgency_level'       => 'medium',
            'recommendations'     => "Contact your local veterinarian for a manual examination.\nMonitor the animal closely and separate from the herd if you notice unusual behaviour.",
            'ai_model'            => $this->model,
        ]);

        return $detection->fresh();
    }
}
