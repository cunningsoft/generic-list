<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use Cunningsoft\GenericList\ListType;
use Cunningsoft\GenericList\SortedListConstructor;

final class SortedDummyList implements ListType
{
    use SortedListConstructor;

    public function getIterator(): ArrayIterator
    {
        return $this->list->getIterator();
    }

    public function first(): Dummy
    {
        return $this->list->first();
    }

    public function last(): Dummy
    {
        return $this->list->last();
    }

    protected function getElementType(): string
    {
        return Dummy::class;
    }

    protected function getSortFunction(): callable
    {
        return static function (Dummy $dummyA, Dummy $dummyB): int {
            return $dummyA->getValue() <=> $dummyB->getValue();
        };
    }
}
