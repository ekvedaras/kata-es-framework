<?php

use ESFramework\EventConsumerStrategy;
use ESFramework\InflectHandlerNamesFromType;
use ESFramework\InMemoryMessageRepository;
use ESFramework\MessageRepository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @template T
 * @param class-string<T>|null $abstract
 * @param array $args
 * @return Container|T
 * @throws BindingResolutionException
 */
function resolve(string $abstract = null, array $args = []) {
    if (is_null($abstract)) {
        return Container::getInstance();
    }

    return Container::getInstance()->make($abstract, $args);
}

resolve()->bind(MessageRepository::class, InMemoryMessageRepository::class);
resolve()->bind(EventConsumerStrategy::class, InflectHandlerNamesFromType::class);
