<?php

declare(strict_types=1);

namespace Domain\BankAccount;

use Domain\Exception\CurrencyMismatchException;
use Domain\Exception\DailyTransactionLimitException;
use Domain\Exception\InsufficientFundsException;

class BankAccount
{
    private float $balance;
    private int $dailyDebitCount;
    private const TRANSACTION_FEE_PERCENTAGE = 0.005;
    private const DAILY_DEBIT_LIMIT = 3;

    public function __construct(
        public readonly string $currency
    ) {
        $this->balance = 0.0;
        $this->dailyDebitCount = 0;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function credit(Payment $payment): void
    {
        if ($payment->getCurrency() !== $this->currency) {
            throw new CurrencyMismatchException();
        }

        $this->balance += $payment->getAmount();
    }

    public function debit(Payment $payment): void
    {
        if ($payment->getCurrency() !== $this->currency) {
            throw new CurrencyMismatchException();
        }

        $transactionFee = $payment->getAmount() * self::TRANSACTION_FEE_PERCENTAGE;
        $totalDebitAmount = $payment->getAmount() + $transactionFee;

        if ($totalDebitAmount > $this->balance) {
            throw new InsufficientFundsException();
        }

        if ($this->dailyDebitCount >= self::DAILY_DEBIT_LIMIT) {
            throw new DailyTransactionLimitException();
        }

        $this->balance -= $totalDebitAmount;
        $this->dailyDebitCount++;
    }
}
