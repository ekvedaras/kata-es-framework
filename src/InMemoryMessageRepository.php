<?php

namespace ESFramework;

use Illuminate\Support\Collection;
use function PHPUnit\Framework\assertNotEmpty;

class InMemoryMessageRepository implements MessageRepository
{
    /** @var Collection<int, Message> */
    private Collection $messages;

    public function __construct()
    {
        $this->messages = collect();
    }

    /**
     * @param string $aggregateRootId
     * @return Collection<int, Message>
     */
    public function retrieveForId(string $aggregateRootId): Collection
    {
        return $this->messages->where('aggregateId', $aggregateRootId);
    }

    public function persist(Message ...$messages): void
    {
        $this->messages->push(...$messages);
    }

    public function assertHasMessages(): void
    {
        assertNotEmpty($this->messages);
    }
}
