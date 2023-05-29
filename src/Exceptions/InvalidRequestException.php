<?php

namespace Bnoordsij\ChatgptApi\Exceptions;

use Exception;

final class InvalidRequestException extends Exception
{
    public static function invalidNumber(): self
    {
        return new static('The number of items requested is invalid, please use a number larger than 0');
    }

    public static function emptyDataStructure(): self
    {
        return new static('The data structure cannot be empty');
    }

    public static function invalidDataStructure(): self
    {
        return new static('The data structure requested is invalid, please check the documentation');
    }
}
