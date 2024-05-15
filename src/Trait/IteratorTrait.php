<?php

namespace UzDevid\WebSocket\Trait;

trait IteratorTrait {
    /**
     * @return void
     */
    public function next(): void {
        next($this->container);
    }

    /**
     * @return int|string
     */
    public function key(): int|string {
        return key($this->container);
    }

    /**
     * @return bool
     */
    public function valid(): bool {
        return key($this->container) !== null;
    }

    /**
     * @return void
     */
    public function rewind(): void {
        reset($this->container);
    }
}