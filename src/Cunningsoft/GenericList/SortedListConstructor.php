<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

/**
 * @template T
 */
trait SortedListConstructor
{
    /**
     * @var SortedGenericList
     * @psalm-var SortedGenericList<T>
     */
    private $list;

    /**
     * @param mixed[] $elements
     *
     * @psalm-param iterable<T> $elements
     */
    public function __construct(iterable $elements = [])
    {
        $this->list = new SortedGenericList($this->getElementType(), $elements);
        $this->list->sort($this->getSortFunction());
    }

    /**
     * @psalm-return class-string<T>
     */
    abstract protected function getElementType(): string;

    abstract protected function getSortFunction(): callable;
}
