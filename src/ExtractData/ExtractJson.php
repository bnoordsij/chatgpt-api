<?php

namespace Bnoordsij\ChatgptApi\ExtractData;

use Bnoordsij\ChatgptApi\Exceptions\InvalidDataException;
use Illuminate\Support\Collection;

class ExtractJson extends AbstractExtractData
{
    public function extractContent(string $response): array|string|null
    {
        $body = $this->extractBody($response);

        // check valid json
        $data = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $body = str_replace([$this->startNeedle, $this->endNeedle], '', $body);
        $data = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $wrappedBody = '[' . $body . ']';
        $data = json_decode($wrappedBody, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $exploded = $this->explodeJson($body);
        if ($exploded->count() > 0) {
            return $exploded;
        }

        throw InvalidDataException::withBody($body);
    }

    private function explodeJson(string $body): Collection
    {
        return collect(explode("\n", $body))
            ->filter()
            ->map(fn (string $line) => trim($line))
            ->map(fn (string $line) => str_replace('},', '}', $line))
            ->map(fn (string $line) => json_decode($line, true))
            ->filter()
            ->map(function (string|array $line) {
                if (is_string($line)) {
                    return $line;
                }

                // all keys are numeric
                // [0, 1, 2] === range(0, 2)
                if (array_keys($line) === range(0, count($line) - 1)) {
                    return current($line);
                }

                return $line;
            });
    }
}
