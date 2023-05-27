<?php

namespace Bnoordsij\ChatgptApi\Api;

use Illuminate\Support\Facades\Http;
use Bnoordsij\ChatgptApi\Contracts\Api\Client as ClientInterface;

final class Client implements ClientInterface
{
    public function __construct(
        private string $baseUrl,
        private string $key,
        private string $model,
    )
    {
    }

    public function getResponse(string $prompt, int $tokens):?string
    {
        $this->call($prompt, $tokens);
    }

    private function call(string $prompt, int $tokens):?string
    {
        $data = [
            'model' => $this->model,
            'prompt' => $prompt,
            'temperature' => 0.7,
            'max_tokens' => $tokens,
            'n' => 1,
            'stop' => '\n',
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->key,
        ];

        $url = 'https://api.openai.com/v1/completions';

        $response = Http::withHeaders($headers)->post($url, $data);

        $json = $response->json();
//        dump(['$json', $json]);

        return $json['choices'][0]['text'] ?? null;
    }
}
