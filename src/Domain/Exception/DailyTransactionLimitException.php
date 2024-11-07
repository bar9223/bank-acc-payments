<?php

declare(strict_types=1);

namespace Domain\Exception;

use Exception;

class DailyTransactionLimitException extends Exception
{
    public function __construct(string $message = 'Daily transaction limit exceeded.')
    {
        parent::__construct($message);
    }
}
