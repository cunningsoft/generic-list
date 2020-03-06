<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

trait UnsortedListConstructor
{
    /** @var UnsortedGenericList */
    private $list;

    /** @param iterable<mixed> $elements */
    public function __construct(iterable $elements = [])
    {
        $this->list = new UnsortedGenericList($this->getElementType(), $elements);
    }

    abstract protected function getElementType(): string;
}
