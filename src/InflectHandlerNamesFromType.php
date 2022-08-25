<?php

namespace ESFramework;

class InflectHandlerNamesFromType implements EventConsumerStrategy
{
    public function getHandlers(object $consumer, Event $event): array
    {
        $reflection = new \ReflectionClass($consumer);

        $methods = [];

        collect($reflection->getMethods(\ReflectionMethod::IS_PROTECTED))
            ->each(function (\ReflectionMethod $method) use (&$methods) {
                $parameter = $method->getParameters()[0] ?? null;

                $type = $parameter?->getType();

                if (! $type) {
                    return;
                }

                $acceptedTypes = match ($type::class) {
                    \ReflectionNamedType::class => [$type],
                    \ReflectionUnionType::class,
                    \ReflectionIntersectionType::class => $type->getTypes(),
                };

                foreach ($acceptedTypes as $type) {
                    $methods[$type->getName()][] = $method->getName();
                }
            });

        return $methods[$event::class] ?? [];
    }
}
