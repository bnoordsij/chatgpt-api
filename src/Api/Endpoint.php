<?php

namespace Bnoordsij\ChatgptApi\Api;

use Bnoordsij\ChatgptApi\Contracts\Api\Client as ClientInterface;
use Bnoordsij\ChatgptApi\Contracts\Api\Endpoint as EndpointContract;
use Bnoordsij\ChatgptApi\Exceptions\InvalidRequestException;
use Bnoordsij\ChatgptApi\ExtractData\ExtractJson;
use Illuminate\Support\Str;

class Endpoint implements EndpointContract
{
    public function __construct(private ClientInterface $client)
    {
    }

    private const START_NEEDLE = 'START:';
    private const END_NEEDLE = 'FINISH';

    protected int $defaultNumber = 10;
    protected string $plural = '';
    protected string $apiDescription = '';
    protected array $showStructure = [
        'title' => 'string|min:3|max:255',
    ];
    protected array $listStructure = [
        'title' => 'string|min:3|max:255',
    ];

    public function list(int $number = 0)
    {
        return $this->getContent($this->buildListMessages($number));
    }

    public function show()
    {
        return $this->getContent($this->buildShowMessages());
    }

    private function getContent(array $messages)
    {
        $response = $this->client->getConversation($messages);

        return (new ExtractJson())->extractContent($response);
    }

    private function buildShowMessages()
    {
        if (empty($this->showStructure)) {
            throw InvalidRequestException::emptyDataStructure();
        }

        return $this->buildMessages($this->showStructure);
    }

    private function buildListMessages(int $number = 0)
    {
        if ($number <= 0 || $number > 100) {
            $number = $this->defaultNumber;
        }
        if (empty($this->listStructure)) {
            throw InvalidRequestException::emptyDataStructure();
        }

        return $this->buildMessages([$this->listStructure], $number);
    }

    private function buildMessages(array $structure, int $number = 1)
    {
        if ($number <= 0 || $number > 100) {
            throw InvalidRequestException::invalidNumber();
        }
        $query = 'Give me ' . ($number === 1 ? Str::singular($this->plural) : $number . ' ' . $this->plural);
        if (empty($structure)) {
            throw InvalidRequestException::emptyDataStructure();
        }
        $json = json_encode($structure);

        return [
            ['role' => 'system', 'content' => 'You are an API with the following description: ' . $this->apiDescription],
            ['role' => 'system', 'content' => 'You start your answer with "' . self::START_NEEDLE . '" followed by the answer in JSON with this format ' . $json . ' and end with "' . self::END_NEEDLE . '"'],
            ['role' => 'system', 'content' => 'If you cannot answer a query, you will give a best estimate'],
//            ['role' => 'system', 'content' => "You are an API returning JSON with this structure: " . $json],
            ['role' => 'user', 'content' => $query . ', using the JSON format'],
        ];
    }
}
