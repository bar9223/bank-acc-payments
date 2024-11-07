<?php

namespace Tests\Domain\BankAccount;

use Domain\BankAccount\BankAccount;
use Domain\BankAccount\Payment;
use Domain\Enum\CurrencyEnum;
use Domain\Exception\CurrencyMismatchException;
use Domain\Exception\DailyTransactionLimitException;
use Domain\Exception\InsufficientFundsException;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    public function testCreditIncreasesBalance(): void
    {
        $account = new BankAccount(CurrencyEnum::USD->value);
        $payment = new Payment(100.0, CurrencyEnum::USD->value);

        $account->credit($payment);

        $this->assertEquals(100.0, $account->getBalance(), 'Balance after crediting should be 100.0');
        $this->assertTrue($account->getBalance() > 0, 'Balance should be positive after crediting.');
    }

    public function testDebitDecreasesBalanceWithTransactionFee(): void
    {
        $account = new BankAccount(CurrencyEnum::USD->value);
        $account->credit(new Payment(100.0, CurrencyEnum::USD->value));

        $account->debit(new Payment(50.0, CurrencyEnum::USD->value));

        $expectedBalance = 100.0 - 50.0 * 1.005;
        $this->assertEquals(
            round($expectedBalance, 2),
            round($account->getBalance(), 2),
            'Balance after debit with transaction fee should match expected balance.'
        );
        $this->assertTrue(
            $account->getBalance() < 100,
            'Balance should be less than the initial 100 after debit.'
        );
    }

    public function testDebitThrowsExceptionForInsufficientFunds(): void
    {
        $this->expectException(InsufficientFundsException::class);
        $this->expectExceptionMessage('Insufficient funds for this transaction.');

        $account = new BankAccount(CurrencyEnum::USD->value);
        $account->credit(new Payment(50.0, CurrencyEnum::USD->value));
        $account->debit(new Payment(100.0, CurrencyEnum::USD->value));
    }

    public function testCurrencyMismatchThrowsException(): void
    {
        $this->expectException(CurrencyMismatchException::class);
        $this->expectExceptionMessage('Payment currency does not match account currency.');

        $account = new BankAccount(CurrencyEnum::USD->value);
        $payment = new Payment(100.0, CurrencyEnum::EUR->value);

        $account->credit($payment);
    }

    public function testDailyDebitLimitThrowsException(): void
    {
        $this->expectException(DailyTransactionLimitException::class);
        $this->expectExceptionMessage('Daily transaction limit exceeded.');

        $account = new BankAccount(CurrencyEnum::USD->value);
        $account->credit(new Payment(1000.0, CurrencyEnum::USD->value));

        $account->debit(new Payment(100.0, CurrencyEnum::USD->value));
        $account->debit(new Payment(100.0, CurrencyEnum::USD->value));
        $account->debit(new Payment(100.0, CurrencyEnum::USD->value));
        $account->debit(new Payment(100.0, CurrencyEnum::USD->value));
    }

    public function testBalanceAfterMultipleCreditsAndDebits(): void
    {
        $account = new BankAccount(CurrencyEnum::USD->value);

        $account->credit(new Payment(100.0, CurrencyEnum::USD->value));
        $account->credit(new Payment(200.0, CurrencyEnum::USD->value));

        $account->debit(new Payment(150.0, CurrencyEnum::USD->value));

        $expectedBalance = (100.0 + 200.0) - (150.0 * 1.005);
        $this->assertEquals(
            round($expectedBalance, 2),
            round($account->getBalance(), 2),
            'Balance after multiple credits and a debit should match expected balance.'
        );
    }
}
