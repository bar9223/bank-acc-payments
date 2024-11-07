<?php

declare(strict_types=1);

namespace Domain\Exception;

use Exception;

class CurrencyMismatchException extends Exception
{
    public function __construct(string $message = 'Payment currency does not match account currency.')
    {
        parent::__construct($message);
    }
}
