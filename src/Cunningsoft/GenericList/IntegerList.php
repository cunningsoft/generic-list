<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use ArrayIterator;
use InvalidArgumentException;
use function array_sum;
use function count;
use function get_class;
use function gettype;
use function is_int;
use function is_object;
use function sprintf;

final class IntegerList implements ListType
{
    /** @var int[] */
    private $elements = [];

    /** @param mixed[] $elements */
    public function __construct(iterable $elements = [])
    {
        foreach ($elements as $element) {
            if (!is_int($element)) {
                throw new InvalidArgumentException(sprintf(
                    'Expected an integer, but got: %s.',
                    is_object($element) ? get_class($element) : gettype($element),
                ));
            }
            $this->elements[] = $element;
        }
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function sum(): int
    {
        return (int) array_sum($this->elements);
    }

    public function average(): float
    {
        if ($this->isEmpty()) {
            return 0.0;
        }

        return $this->sum() / $this->count();
    }

    /** @return ArrayIterator|int[] */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }
}
