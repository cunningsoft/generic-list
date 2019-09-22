<?php

declare(strict_types=1);

namespace Tests;

use ArrayIterator;
use Cunningsoft\GenericList\ListType;
use Cunningsoft\GenericList\UnsortedListConstructor;

final class UnsortedDummyList implements ListType
{
    use UnsortedListConstructor;

    public function getIterator(): ArrayIterator
    {
        return $this->list->getIterator();
    }

    protected function getElementType(): string
    {
        return Dummy::class;
    }
}
