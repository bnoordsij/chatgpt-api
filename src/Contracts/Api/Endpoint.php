<?php

namespace Bnoordsij\ChatgptApi\Contracts\Api;

interface Endpoint
{
    public function list(int $number = 0);

    public function show(string $title = '');
}
