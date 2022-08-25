<?php

namespace Bank\Exceptions;

class BalanceException extends \DomainException
{
    public static function insufficientBalance(): self
    {
        return new self('Insufficient balance');
    }
}
