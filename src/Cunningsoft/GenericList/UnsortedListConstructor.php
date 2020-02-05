<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

/**
 * @template T
 */
trait UnsortedListConstructor
{
    /**
     * @var UnsortedGenericList
     * @psalm-var UnsortedGenericList<T>
     */
    private $list;

    /**
     * @param mixed[] $elements
     *
     * @psalm-param iterable<T> $elements
     */
    public function __construct(iterable $elements = [])
    {
        $this->list = new UnsortedGenericList($this->getElementType(), $elements);
    }

    /**
     * @psalm-return class-string<T>
     */
    abstract protected function getElementType(): string;
}
