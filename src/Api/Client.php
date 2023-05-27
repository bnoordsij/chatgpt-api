<?php

namespace Bnoordsij\ChatgptApi\Api;

use Illuminate\Support\Facades\Http;
use Bnoordsij\ChatgptApi\Exceptions\InvalidRequestException;
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

    /*
     * @param array $messages
     * [
     *   ['role' => 'user', 'content' => 'What is the capital of France'],
     *   ['role' => 'assistant', 'content' => 'Paris'],
     * ]
     */
    public function getConversation(array $messages)
    {
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
//            'max_tokens' => $tokens,
        ];

        return $this->call('chat/completions', $data)[0]['message']['content'] ?? null;
    }

    public function getResponse(string $prompt, int $tokens)
    {
        $data = [
            'prompt' => $prompt,
            'max_tokens' => $tokens,
            'temperature' => 0.7,
            'n' => 1,
            'stop' => '\n',
        ];

        return $this->call('completions', $data)[0]['text'] ?? null;
    }

    private function call(string $endpoint, array $data)
    {
        $data['model'] ??= $this->model;

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->key,
        ];

        $url = 'https://api.openai.com/v1/' . $endpoint;

        dump(['$data', $data]);

        // @todo switch to guzzle client
        $response = Http::withHeaders($headers)->post($url, $data);

        $json = $response->json();
        dump(['$json', $json]);

        if (isset($json['error'])) {
            if (isset($json['error']['message'])) {
                throw new InvalidRequestException($json['error']['message']);
            }
            throw new InvalidRequestException('Error: ' . json_encode($json));
        }

        return $json['choices'] ?? [];
    }
}
