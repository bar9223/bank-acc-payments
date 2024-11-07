<?php

declare(strict_types=1);

namespace Domain\BankAccount;

class Payment
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
