<?php

namespace Bank\Aggregates;

use Bank\Events\AmountCredited;
use Bank\Events\AmountDebited;
use Bank\Exceptions\BalanceException;
use ESFramework\AggregateRoot;

final class BankAccount extends AggregateRoot
{
    private int $balance = 0;

    public function credit(int $amount): self
    {
        return $this->recordThat(new AmountCredited($amount));
    }

    protected function applyAmountCredited(AmountCredited $event): void
    {
        $this->balance += $event->amount;
    }

    public function debit(int $amount): self
    {
        if ($this->balance - $amount < 0) {
            throw BalanceException::insufficientBalance();
        }

        return $this->recordThat(new AmountDebited($amount));
    }

    protected function applyAmountDebited(AmountDebited $event): void
    {
        $this->balance -= $event->amount;
    }
}
