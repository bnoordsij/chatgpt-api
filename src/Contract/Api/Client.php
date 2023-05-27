<?php

namespace Bnoordsij\ChatgptApi\Contract\Api;

interface Client
{
    public function getResponse(string $prompt, int $tokens);
}
