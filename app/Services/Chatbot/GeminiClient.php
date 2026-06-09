<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Thin wrapper around the Google Gemini REST API (free tier).
 * No SDK required — uses Laravel's HTTP client.
 */
class GeminiClient
{
    public function isConfigured(): bool
    {
        return filled(config('chatbot.gemini.api_key'));
    }

    /**
     * Generate a reply.
     *
     * @param  string  $systemPrompt  Personality + guardrails + grounded knowledge.
     * @param  array<int, array{role:string, text:string}>  $history  Prior turns.
     * @param  string  $userMessage   The latest user message.
     * @return string|null  The model's text reply, or null on failure.
     */
    public function generate(string $systemPrompt, array $history, string $userMessage): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $config = config('chatbot.gemini');

        $contents = [];
        foreach ($history as $turn) {
            $text = trim((string) ($turn['text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $contents[] = [
                'role'  => ($turn['role'] ?? 'user') === 'bot' ? 'model' : 'user',
                'parts' => [['text' => $text]],
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $url = rtrim($config['endpoint'], '/') . "/models/{$config['model']}:generateContent";

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature'     => 0.3,
                'maxOutputTokens' => 600,
            ],
        ];

        // Retry once on transient overload / rate-limit (429, 500, 503).
        $maxAttempts = 2;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $response = Http::timeout($config['timeout'])
                    ->withHeaders(['x-goog-api-key' => $config['api_key']])
                    ->acceptJson()
                    ->asJson()
                    ->post($url, $payload);
            } catch (Throwable $exception) {
                Log::warning('Gemini request threw an exception.', ['message' => $exception->getMessage()]);

                return null;
            }

            if ($response->successful()) {
                $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

                return is_string($text) && trim($text) !== '' ? trim($text) : null;
            }

            if ($attempt < $maxAttempts && in_array($response->status(), [429, 500, 503], true)) {
                usleep(1200000); // 1.2s back-off, then retry

                continue;
            }

            Log::warning('Gemini request failed.', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return null;
        }

        return null;
    }
}
