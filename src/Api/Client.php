<?php

namespace Bnoordsij\ChatgptApi\Api;

use Bnoordsij\ChatgptApi\Contracts\Api\Client as ClientInterface;
use Bnoordsij\ChatgptApi\Exceptions\InvalidOutputException;
use Bnoordsij\ChatgptApi\Exceptions\InvalidRequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

final class Client implements ClientInterface
{
    public function __construct(
        private string $baseUrl,
        private string $key,
        private string $model,
    )
    {
    }

    // later this should be an interface of Wrapper
    public function duplicateJson(Model $model, string $question)
    {
        $exampleJson = $model->toJsonResponse();
        $json = json_encode([$exampleJson, ['title' => '...']]);
        $messages = [
            ['role' => 'system', 'content' => 'You return all your responses in JSON like: ' . $json],
//            ['role' => 'user', 'content' => $initialQuestion],
//            ['role' => 'assistant', 'content' => $json],
            ['role' => 'user', 'content' => $question],
        ];

//        dd('$messages', $messages);

        return $this->getConversation($messages);
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

        return $json['choices'] ?? throw new InvalidOutputException('Error: ' . json_encode($json));;
    }
}
