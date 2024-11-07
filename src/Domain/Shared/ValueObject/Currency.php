<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

class Currency
{
    public function __construct(
        public readonly string $code
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->getCode();
    }
}
