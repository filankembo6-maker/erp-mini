<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MistralService
{
    public function ask(string $system, string $user): ?string
    {
        $key = config('services.mistral.key');

        if (!$key) {
            Log::warning('Mistral API key missing.');
            return null;
        }

        try {
            $response = Http::withToken($key)
                ->timeout(30)
                ->post('https://api.mistral.ai/v1/chat/completions', [
                    'model' => config('services.mistral.model', 'mistral-small-latest'),
                    'temperature' => 0.3,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $user],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Mistral API error: ' . $response->body());
                return null;
            }

            return $response->json('choices.0.message.content');
        } catch (\Throwable $e) {
            Log::error('Mistral exception: ' . $e->getMessage());
            return null;
        }
    }
}