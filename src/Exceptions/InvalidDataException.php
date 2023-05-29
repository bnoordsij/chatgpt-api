<?php

namespace Bnoordsij\ChatgptApi\Exceptions;

use Exception;
use Throwable;

class InvalidDataException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null, private string $body = '')
    {
        parent::__construct($message, $code, $previous);

        if ($this->body) {
            logger($this->body);
        }
    }

    public static function withBody(string $body, string $message = 'Error: invalid body'): static
    {
        return new static($message, 0, null, $body);
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
