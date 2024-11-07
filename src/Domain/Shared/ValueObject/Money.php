<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

use Domain\Exception\CurrencyMismatchException;

class Money
{
    private const NOT_MATCH_EXCEPTION = 'Currencies do not match.';

    public function __construct(
        public readonly float $amount,
        public readonly Currency $currency,
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(Money $money): Money
    {
        if (!$this->currency->equals($money->getCurrency())) {
            throw new CurrencyMismatchException(self::NOT_MATCH_EXCEPTION);
        }

        return new Money($this->amount + $money->getAmount(), $this->currency);
    }
}
