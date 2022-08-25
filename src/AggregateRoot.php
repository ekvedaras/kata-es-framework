<?php

namespace ESFramework;

use Illuminate\Support\Str;

abstract class AggregateRoot
{
    private array $recordedEvents = [];

    public function __construct(
        private readonly string $aggregateRootId,
        private readonly MessageRepository $messageRepository,
        private readonly EventConsumerStrategy $eventConsumerStrategy,
    )
    {
    }

    public static function retrieve(string $aggregateRootId): static
    {
        $aggregate = resolve(static::class, ['aggregateRootId' => $aggregateRootId]);

        $messages = $aggregate->messageRepository->retrieveForId($aggregateRootId);

        $events = $messages->map->event;

        return $aggregate->reconstituteFromEvents($events);
    }

    /** @param iterable<int, Event> $events */
    private function reconstituteFromEvents(iterable $events): static
    {
        foreach ($events as $event) {
            $this->apply($event);
        }

        return $this;
    }

    public function persist(): static
    {
        $messages = collect($this->recordedEvents)->map(fn (Event $event) => new Message(
            aggregateRootId: $this->aggregateRootId,
            event: $event,
        ));

        $this->messageRepository->persist(...$messages->all());

        $this->recordedEvents = [];

        return $this;
    }

    /**
     * @return array<int, Event>
     */
    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    protected function recordThat(Event $event): static
    {
        $this->recordedEvents[] = $event;
        $this->apply($event);

        return $this;
    }

    private function apply(Event $event): void
    {
        $handlers = $this->eventConsumerStrategy->getHandlers($this, $event);

        foreach ($handlers as $handler) {
            $this->{$handler}($event);
        }
    }
}
