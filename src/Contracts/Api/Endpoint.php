<?php

namespace Bnoordsij\ChatgptApi\Contracts\Api;

interface Endpoint
{
    public function listAboutSubject(int $number = 0, string $subject): array;

    public function list(int $number = 0, string $query = ''): array;

    public function showByTitle(string $title);

    public function show(string $query = '');
}
