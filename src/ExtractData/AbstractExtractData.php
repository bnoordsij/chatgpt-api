<?php

namespace Bnoordsij\ChatgptApi\ExtractData;

use Bnoordsij\ChatgptApi\Exceptions\InvalidDataException;

abstract class AbstractExtractData
{
    public function __construct(
        protected string $startNeedle = 'START:',
        protected string $endNeedle = 'FINISH',
    )
    {
    }

    abstract public function extractContent(string $response);

    protected function extractBody(string $response): string
    {
        $start = strpos($response, $this->startNeedle) + strlen($this->startNeedle);
        $end = strrpos($response, $this->endNeedle);
        if ($start === false) {
            throw new InvalidDataException('The API did not return a valid response', 0, null, $response);
        }
        if ($end === false) {
            // we need to do a paginated call
            throw new InvalidDataException('Requested data too large, see documentation for suggestions', 0, null, $response);
        }

        $body = substr($response, $start, $end - $start);

        return trim($body);
    }
}
