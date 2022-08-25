<?php

namespace Bank\Events;

use ESFramework\Event;

class AmountCredited extends Event
{
    public function __construct(
        public readonly int $amount,
    )
    {
    }
}
