<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use function usort;

/**
 * This class must only be used in Sorted*List implementations. It provides a layer on top of php's array to ease
 * the creation of concrete list classes like SortedByAgeUserList or SortedByTitleBlogPostList. It also makes sure
 * it's elements are in the order defined in the sort callback.
 */
final class SortedGenericList extends GenericList
{
    public function sort(callable $callback): void
    {
        usort($this->elements, $callback);
    }

    /** @return mixed */
    public function atIndex(int $index)
    {
        if ($index < 0 || $index > $this->count() - 1) {
            throw new NoElementAtIndex($index);
        }

        return $this->elements[$index];
    }

    /** @return mixed|null */
    public function atIndexOrNull(int $index)
    {
        if ($index < 0 || $index > $this->count() - 1) {
            return null;
        }

        return $this->elements[$index];
    }

    /** @return mixed */
    public function first()
    {
        if ($this->isEmpty()) {
            throw new NoElements();
        }

        return $this->atIndex(0);
    }

    /** @return mixed|null */
    public function firstOrNull()
    {
        return $this->atIndexOrNull(0);
    }

    /** @return mixed */
    public function last()
    {
        if ($this->isEmpty()) {
            throw new NoElements();
        }

        return $this->atIndex($this->count() - 1);
    }

    /** @return mixed|null */
    public function lastOrNull()
    {
        return $this->atIndexOrNull($this->count() - 1);
    }
}
