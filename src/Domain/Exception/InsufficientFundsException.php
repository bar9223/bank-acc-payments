<?php

declare(strict_types=1);

namespace Domain\Exception;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct(string $message = 'Insufficient funds for this transaction.')
    {
        parent::__construct($message);
    }
}
