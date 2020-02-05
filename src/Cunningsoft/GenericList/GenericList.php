<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use function array_filter;
use function array_map;
use function array_merge;
use function array_slice;
use function array_walk;
use function count;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

/**
 * @psalm-template T
 */
abstract class GenericList implements IteratorAggregate
{
    /**
     * @var string
     * @psalm-var class-string<T>
     */
    private $type;

    /**
     * @var mixed[]
     * @psalm-var T[]
     */
    protected $elements = [];

    /**
     * @param mixed[] $elements
     *
     * @psalm-param class-string<T> $type
     * @psalm-param iterable<T> $elements
     */
    public function __construct(string $type, iterable $elements = [])
    {
        $this->type = $type;
        foreach ($elements as $element) {
            if (!$element instanceof $type) {
                throw new InvalidArgumentException(sprintf(
                    'Expected a %s, but got: %s.',
                    $type,
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

    public function filter(callable $callback): self
    {
        return new static($this->type, array_filter($this->elements, $callback));
    }

    /** @return mixed[] */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->elements);
    }

    public function walk(callable $callback): bool
    {
        return array_walk($this->elements, $callback);
    }

    public function merge(self $other): self
    {
        return new static($this->type, array_merge($this->elements, $other->elements));
    }

    public function slice(int $offset, ?int $length = null): self
    {
        return new static($this->type, array_slice($this->elements, $offset, $length));
    }

    /**
     * @return mixed
     *
     * @psalm-return T
     */
    public function only()
    {
        if ($this->isEmpty()) {
            throw new NoElements();
        }

        if ($this->count() > 1) {
            throw new ElementsAreNotUnique();
        }

        return $this->elements[0];
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }
}
