<?php

namespace App\Services\MjGuide;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiProvider
{
    /**
     * @param array<int, array{role:string,text:string}> $messages oldest-first conversation turns
     */
    public function chat(string $systemPrompt, array $messages): string
    {
        $key = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        if (empty($key)) {
            throw new RuntimeException('Gemini API key is not configured');
        }

        $contents = [];
        foreach ($messages as $m) {
            $contents[] = [
                'role' => $m['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $m['text']]],
            ];
        }

        $response = Http::timeout(15)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}",
            [
                'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 600,
                ],
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException('Gemini HTTP '.$response->status());
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Gemini returned an empty or blocked response');
        }

        return trim($text);
    }
}
