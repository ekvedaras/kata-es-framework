<?php

namespace ESFramework;

final class Message
{
    public function __construct(
        public readonly string $aggregateRootId,
        public readonly Event $event,
    )
    {
    }
}
