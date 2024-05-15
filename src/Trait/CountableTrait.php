<?php

namespace UzDevid\WebSocket\Trait;

/**
 * @property array $container
 */
trait CountableTrait {
    /**
     * @return int
     */
    public function count(): int {
        return count($this->container);
    }
}