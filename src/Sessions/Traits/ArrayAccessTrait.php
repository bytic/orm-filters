<?php

namespace Nip\Records\Filters\Sessions\Traits;

/**
 * Trait ArrayAccessTrait
 * @package Nip\Records\Filters\Sessions\Traits
 */
trait ArrayAccessTrait
{

    /**
     * Determine if the given configuration option exists.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->filters[$key]) || array_key_exists($key, $this->filters);
    }

    /**
     * Get a configuration option.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->filters[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (is_null($key)) {
            $this->filters[] = $value;
        } else {
            $this->filters[$key] = $value;
        }
    }

    /**
     * Unset a configuration option.
     *
     * @inheritdoc
     */
    public function offsetUnset(mixed $key): void
    {
        unset($this->filters[$key]);
    }
}
