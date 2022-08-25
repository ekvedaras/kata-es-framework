<?php

namespace Bank\Events;

use ESFramework\Event;

class AmountDebited extends Event
{
    public function __construct(
        public readonly int $amount,
    )
    {
    }

}
