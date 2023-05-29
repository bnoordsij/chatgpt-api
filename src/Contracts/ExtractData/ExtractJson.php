<?php

namespace Bnoordsij\ChatgptApi\Contracts\ExtractData;

interface ExtractJson
{
    public function extractContent(string $response): array|string|null;
}
