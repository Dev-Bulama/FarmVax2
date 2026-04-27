<?php

namespace App\Services;

use App\Models\DiseaseDetection;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiseaseDetectionService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;

    protected string $anthropicUrl = 'https://api.anthropic.com/v1/messages';
    protected string $openaiUrl    = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->provider = (string) (Setting::get('disease_detection_provider') ?? 'anthropic');

        if ($this->provider === 'openai') {
            $this->apiKey = (string) (Setting::get('disease_detection_openai_key')
                ?? config('services.openai.key')
                ?? env('OPENAI_API_KEY')
                ?? '');
            $this->model = (string) (Setting::get('disease_detection_openai_model') ?? 'gpt-4o');
        } else {
            $this->apiKey = (string) (Setting::get('disease_detection_anthropic_key')
                ?? config('services.anthropic.key')
                ?? env('ANTHROPIC_API_KEY')
                ?? '');
            $this->model = (string) (Setting::get('disease_detection_anthropic_model') ?? 'claude-sonnet-4-6');
        }
    }

    public function analyse(DiseaseDetection $detection): DiseaseDetection
    {
        if (empty($this->apiKey)) {
            return $this->fallbackAnalysis(
                $detection,
                'No API key configured. Go to Admin → Settings → Disease Detection AI to add one.'
            );
        }

        try {
            $imagePath = storage_path('app/public/' . $detection->image_path);
            if (!file_exists($imagePath)) {
                return $this->fallbackAnalysis($detection, 'Image file not found on disk.');
            }

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType  = mime_content_type($imagePath);
            $symptoms  = $detection->symptoms_reported ?? 'No symptoms reported by the user.';
            $hintType  = $detection->animal_type ?? '';

            $prompt = $this->buildPrompt($hintType, $symptoms);

            $result = $this->provider === 'openai'
                ? $this->callOpenAI($imageData, $mimeType, $prompt)
                : $this->callAnthropic($imageData, $mimeType, $prompt);

            if (!$result) {
                return $this->fallbackAnalysis($detection, 'Could not parse AI response as JSON.');
            }

            // ── Not an animal ─────────────────────────────────────────────
            if (isset($result['is_animal']) && $result['is_animal'] === false) {
                $detection->update([
                    'status'              => 'completed',
                    'animal_type'         => 'not_animal',
                    'is_sick'             => false,
                    'confidence_score'    => 95,
                    'urgency_level'       => 'low',
                    'analysis_result'     => $result['not_animal_reason'] ?? 'The image does not appear to contain a livestock animal.',
                    'detected_conditions' => [],
                    'recommendations'     => "Please upload a clear, close-up photo of the animal you want to scan.\nMake sure the animal fills most of the frame with good lighting.",
                    'ai_model'            => $this->provider . '/' . $this->model,
                ]);
                return $detection->fresh();
            }

            // ── Image quality prefix (stored in analysis_result) ──────────
            $qualityPrefix = '';
            $imageQuality  = $result['image_quality'] ?? 'good';
            if (in_array($imageQuality, ['poor', 'fair'])) {
                $note          = $result['image_quality_note'] ?? 'Image quality is low — results may be less accurate.';
                $qualityPrefix = '[POOR_IMAGE: ' . $note . '] ';
            }

            // ── Detected animal type (auto-detected by AI) ────────────────
            $detectedType = trim($result['detected_animal_type'] ?? '');
            $animalType   = $detectedType ?: ($hintType ?: 'livestock');

            $detection->update([
                'status'              => 'completed',
                'animal_type'         => $animalType,
                'is_sick'             => (bool) ($result['is_sick'] ?? false),
                'confidence_score'    => min(100, max(0, (float) ($result['confidence_score'] ?? 75))),
                'urgency_level'       => $result['urgency_level'] ?? 'low',
                'analysis_result'     => $qualityPrefix . ($result['summary'] ?? ''),
                'detected_conditions' => $result['detected_conditions'] ?? [],
                'recommendations'     => implode("\n", (array) ($result['recommendations'] ?? [])),
                'ai_model'            => $this->provider . '/' . $this->model,
            ]);

        } catch (\Throwable $e) {
            Log::error('DiseaseDetection exception', ['message' => $e->getMessage()]);
            return $this->fallbackAnalysis($detection, $e->getMessage());
        }

        return $detection->fresh();
    }

    // ── Anthropic (Claude Vision) ────────────────────────────────────────────

    protected function callAnthropic(string $imageData, string $mimeType, string $prompt): ?array
    {
        $response = Http::timeout(60)->withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post($this->anthropicUrl, [
            'model'      => $this->model,
            'max_tokens' => 1024,
            'messages'   => [[
                'role'    => 'user',
                'content' => [
                    ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mimeType, 'data' => $imageData]],
                    ['type' => 'text',  'text'   => $prompt],
                ],
            ]],
        ]);

        if (!$response->successful()) {
            Log::error('DiseaseDetection Anthropic error', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('Anthropic API error ' . $response->status() . ': ' . $response->body());
        }

        return $this->parseJson($response->json('content.0.text', ''));
    }

    // ── OpenAI (GPT-4 Vision) ────────────────────────────────────────────────

    protected function callOpenAI(string $imageData, string $mimeType, string $prompt): ?array
    {
        $response = Http::timeout(60)->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->openaiUrl, [
            'model'      => $this->model,
            'max_tokens' => 1024,
            'messages'   => [[
                'role'    => 'user',
                'content' => [
                    [
                        'type'      => 'image_url',
                        'image_url' => ['url' => "data:{$mimeType};base64,{$imageData}", 'detail' => 'high'],
                    ],
                    ['type' => 'text', 'text' => $prompt],
                ],
            ]],
        ]);

        if (!$response->successful()) {
            Log::error('DiseaseDetection OpenAI error', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('OpenAI API error ' . $response->status() . ': ' . $response->body());
        }

        return $this->parseJson($response->json('choices.0.message.content', ''));
    }

    // ── Shared helpers ───────────────────────────────────────────────────────

    protected function buildPrompt(string $hintType, string $symptoms): string
    {
        $hintLine = $hintType
            ? "User-provided animal type hint (use only if image confirms it): {$hintType}"
            : "Animal type hint: not provided — auto-detect from the image.";

        return <<<PROMPT
You are a world-class veterinary AI assistant with deep expertise in diagnosing diseases of livestock and farm animals (cattle, goat, sheep, pig, poultry, horse, rabbit, dog, cat, etc.).

STEP 1 — Validate the image:
• Is there a real animal in this image? If not (e.g. it is a person, object, scenery, or unclear image), set "is_animal" to false and explain in "not_animal_reason". Do NOT attempt a disease diagnosis.
• Rate image quality: "good" (clear, well-lit, animal visible), "fair" (slightly blurry or dark but usable), or "poor" (too blurry, dark, or distant for reliable analysis).

STEP 2 — If an animal is present:
• AUTO-DETECT the exact species/breed (e.g. "cattle", "goat", "sheep", "pig", "poultry", "horse", "rabbit"). Do not just say "livestock".
• Identify ALL visible signs of disease, injury, malnutrition, or abnormality.
• List up to 5 most likely diagnoses with probability percentages summing to 100%.
• Assess urgency and give clear farmer-friendly recommendations.

{$hintLine}
User-reported symptoms: {$symptoms}

CRITICAL: Your response must be ONLY valid JSON — no markdown, no code fences, no explanatory text before or after. Start immediately with { and end with }.

Use exactly this structure:
{
  "is_animal": true,
  "not_animal_reason": null,
  "image_quality": "good",
  "image_quality_note": null,
  "detected_animal_type": "<auto-detected species, e.g. cattle>",
  "is_sick": true,
  "confidence_score": 85,
  "urgency_level": "medium",
  "summary": "<2-3 sentence plain-language summary for the farmer>",
  "detected_conditions": [
    {
      "name": "<condition name>",
      "probability": 60,
      "severity": "moderate",
      "description": "<brief description>"
    }
  ],
  "visible_symptoms": ["<symptom 1>", "<symptom 2>"],
  "recommendations": [
    "<clear actionable step 1>",
    "<clear actionable step 2>",
    "<clear actionable step 3>"
  ],
  "requires_vet": true,
  "isolation_recommended": false
}
PROMPT;
    }

    protected function parseJson(string $text): ?array
    {
        $text = trim($text);

        // 1. Try direct decode (ideal: model returned pure JSON)
        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // 2. Strip markdown code fences and retry
        $stripped = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/i', '$1', $text);
        $decoded  = json_decode(trim($stripped), true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // 3. Extract the outermost JSON object from anywhere in the text
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $decoded = json_decode(substr($text, $start, $end - $start + 1), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        Log::warning('DiseaseDetection: could not parse JSON', ['raw' => substr($text, 0, 500)]);
        return null;
    }

    protected function fallbackAnalysis(DiseaseDetection $detection, string $reason): DiseaseDetection
    {
        Log::warning('DiseaseDetection fallback triggered', ['reason' => $reason]);

        $detection->update([
            'status'              => 'failed',
            'analysis_result'     => 'Automated analysis is temporarily unavailable. ' . $reason,
            'confidence_score'    => 0,
            'urgency_level'       => 'medium',
            'recommendations'     => "Contact your local veterinarian for a manual examination.\nMonitor the animal closely and separate from the herd if you notice unusual behaviour.",
            'ai_model'            => $this->provider . '/' . $this->model,
        ]);

        return $detection->fresh();
    }
}
