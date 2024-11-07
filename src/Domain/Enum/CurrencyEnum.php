<?php

declare(strict_types=1);

namespace Domain\Enum;

enum CurrencyEnum: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case PLN = 'PLN';
}
