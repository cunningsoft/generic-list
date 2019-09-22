<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use ArrayIterator;
use InvalidArgumentException;
use const SORT_REGULAR;
use function array_merge;
use function array_slice;
use function array_unique;
use function array_walk;
use function count;
use function get_class;
use function gettype;
use function implode;
use function is_object;
use function is_string;
use function sprintf;

final class StringList implements ListType
{
    /** @var string[] */
    private $elements = [];

    /** @param mixed[] $elements */
    public function __construct(iterable $elements = [])
    {
        foreach ($elements as $element) {
            if (!is_string($element)) {
                throw new InvalidArgumentException(sprintf(
                    'Expected a string, but got: %s.',
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

    public function merge(self $other): self
    {
        return new self(array_merge($this->elements, $other->elements));
    }

    public function slice(int $offset, ?int $length = null): self
    {
        return new self(array_slice($this->elements, $offset, $length));
    }

    public function distinct(): self
    {
        return new self(array_unique($this->elements, SORT_REGULAR));
    }

    public function walk(callable $callback): bool
    {
        return array_walk($this->elements, $callback);
    }

    public function implode(string $glue = ''): string
    {
        return implode($glue, $this->elements);
    }

    /** @return ArrayIterator|string[] */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }
}
