<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @template T
 * @param class-string<T> $abstract
 * @param ...$args
 * @return Container|T
 * @throws BindingResolutionException
 */
function resolve(string $abstract = null, $args = []) {
    if (is_null($abstract)) {
        return Container::getInstance();
    }

    return Container::getInstance()->make($abstract, $args);
}

resolve()->bind(\ESFramework\MessageRepository::class, \ESFramework\InMemoryMessageRepository::class);
resolve()->bind(\ESFramework\EventConsumerStrategy::class, \ESFramework\InflectHandlerNamesFromType::class);
