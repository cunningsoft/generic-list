<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use IteratorAggregate;

/**
 * Every concrete list implementation has to implement this interface, to make instantiation and iteration consitent.
 *
 * @template T
 */
interface ListType extends IteratorAggregate
{
    /**
     * @param object[] $elements
     *
     * @psalm-param T[] $elements
     */
    public function __construct(iterable $elements = []);
}
