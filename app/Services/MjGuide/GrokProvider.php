<?php

namespace App\Services\MjGuide;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GrokProvider
{
    /**
     * @param array<int, array{role:string,text:string}> $messages oldest-first conversation turns
     */
    public function chat(string $systemPrompt, array $messages): string
    {
        $key = config('services.grok.key');
        $model = config('services.grok.model', 'grok-3-mini');

        if (empty($key)) {
            throw new RuntimeException('Grok API key is not configured');
        }

        $chat = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($messages as $m) {
            $chat[] = [
                'role' => $m['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $m['text'],
            ];
        }

        $response = Http::withToken($key)
            ->timeout(20)
            ->post('https://api.x.ai/v1/chat/completions', [
                'model' => $model,
                'messages' => $chat,
                'temperature' => 0.4,
                'max_tokens' => 800,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Grok HTTP '.$response->status());
        }

        $text = $response->json('choices.0.message.content');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Grok returned an empty response');
        }

        return trim($text);
    }
}
