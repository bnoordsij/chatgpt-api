<?php

namespace Bnoordsij\ChatgptApi\Contracts\Api;

interface Client
{
    public function getResponse(string $prompt, int $tokens);
}
