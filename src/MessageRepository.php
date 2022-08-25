<?php

namespace ESFramework;

use Illuminate\Support\Collection;

interface MessageRepository
{
    /**
     * @return Collection<int, Message>
     */
    public function retrieveForId(string $aggregateRootId): Collection;

    public function persist(Message ...$messages): void;
}
