<?php

namespace App\Exceptions;

use RuntimeException;

class GameJoltDataStoreFetchException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $column,
        public readonly string $datastoreKey,
        public readonly ?string $apiMessage = null,
    ) {
        parent::__construct($message);
    }
}
