<?php

namespace UzDevid\WebSocket\Trait;

trait IteratorTrait {
    /**
     * @return mixed
     */
    public function current(): mixed {
        return current($this->container);
    }

    /**
     * @return void
     */
    public function next(): void {
        next($this->container);
    }

    /**
     * @return int
     */
    public function key(): int {
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