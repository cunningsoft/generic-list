<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

trait SortedListConstructor
{
    /** @var SortedGenericList */
    private $list;

    /** @param iterable<mixed> $elements */
    public function __construct(iterable $elements = [])
    {
        $this->list = new SortedGenericList($this->getElementType(), $elements);
        $this->list->sort($this->getSortFunction());
    }

    abstract protected function getElementType(): string;

    abstract protected function getSortFunction(): callable;
}
