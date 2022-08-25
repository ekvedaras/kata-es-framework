<?php

namespace Bank\Tests\Aggregates;

use Bank\Aggregates\BankAccount;
use Bank\Events\AmountCredited;
use Bank\Events\AmountDebited;
use Bank\Exceptions\BalanceException;
use ESFramework\InMemoryMessageRepository;
use ESFramework\MessageRepository;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    public function test_it_can_credit_an_amount()
    {
        $account = BankAccount::retrieve('123');

        $account->credit(100);

        $recordedEvents = $account->recordedEvents();

        $this->assertContainsEquals(new AmountCredited(100), $recordedEvents);
    }

    public function test_it_can_debit_an_amount()
    {
        $account = BankAccount::retrieve('123')
            ->credit(100);

        $account->debit(50);

        $recordedEvents = $account->recordedEvents();

        $this->assertContainsEquals(new AmountDebited(50), $recordedEvents);
    }

    public function test_it_throws_insufficient_funds_when_balance_is_too_low()
    {
        $account = BankAccount::retrieve('123');

        $this->expectExceptionObject(BalanceException::insufficientBalance());

        $account->debit(100);
    }

    public function test_it_can_persist()
    {
        $repository = resolve()->instance(MessageRepository::class, new InMemoryMessageRepository());

        BankAccount::retrieve('123')
            ->credit(100)
            ->persist();

        $repository->assertHasMessages();
    }
}
