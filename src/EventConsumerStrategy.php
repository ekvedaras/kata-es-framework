<?php

namespace ESFramework;

interface EventConsumerStrategy
{
    /** @return string[] */
    public function getHandlers(object $consumer, Event $event): array;
}
