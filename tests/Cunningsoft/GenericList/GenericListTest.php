<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use ArrayIterator;
use Closure;
use Cunningsoft\GenericList\ElementsAreNotUnique;
use Cunningsoft\GenericList\GenericList;
use Cunningsoft\GenericList\NoElements;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Dummy;
use Tests\DummyGenericList;

final class GenericListTest extends TestCase
{
    /** @var int */
    private $callbackCounter = 0;

    /**
     * @param Dummy[] $values
     *
     * @test
     * @dataProvider provideConstructor
     */
    public function it_can_be_initialised(array $values): void
    {
        $exceptionThrown = false;

        try {
            new DummyGenericList(Dummy::class, $values);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        Assert::assertFalse($exceptionThrown);
    }

    public function provideConstructor(): array
    {
        return [
            [[]],
            [[new Dummy(1)]],
            [[new Dummy(1), new Dummy(2)]],
        ];
    }

    /**
     * @param mixed $invalidType
     *
     * @test
     * @dataProvider provideInvalidType
     */
    public function it_fails_on_passing_an_invalid_type($invalidType): void
    {
        $this->expectException(InvalidArgumentException::class);

        new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2), $invalidType]);
    }

    public function provideInvalidType(): array
    {
        return [
            [1],
            ['foo'],
            [1.5],
            [true],
        ];
    }

    /**
     * @test
     * @dataProvider provideCount
     */
    public function it_can_count_its_elements(GenericList $list, int $expectedCount): void
    {
        Assert::assertSame($expectedCount, $list->count());
    }

    public function provideCount(): array
    {
        return [
            [new DummyGenericList(Dummy::class), 0],
            [new DummyGenericList(Dummy::class, [new Dummy(1)]), 1],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 2],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsEmpty
     */
    public function it_can_check_if_it_is_empty(GenericList $list, bool $expectedIsEmpty): void
    {
        Assert::assertSame($expectedIsEmpty, $list->isEmpty());
    }

    public function provideIsEmpty(): array
    {
        return [
            [new DummyGenericList(Dummy::class), true],
            [new DummyGenericList(Dummy::class, [new Dummy(1)]), false],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), false],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsNotEmpty
     */
    public function it_can_check_if_it_is_not_empty(GenericList $list, bool $expectedIsNotEmpty): void
    {
        Assert::assertSame($expectedIsNotEmpty, $list->isNotEmpty());
    }

    public function provideIsNotEmpty(): array
    {
        return [
            [new DummyGenericList(Dummy::class), false],
            [new DummyGenericList(Dummy::class, [new Dummy(1)]), true],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), true],
        ];
    }

    /**
     * @test
     * @dataProvider provideFilter
     */
    public function it_can_filter_its_elements(DummyGenericList $list, Closure $closure, DummyGenericList $expectedList): void
    {
        Assert::assertEquals($expectedList, $list->filter($closure));
    }

    public function provideFilter(): array
    {
        return [
            [
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]),
                static function (): bool {
                    return true;
                },
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]),
            ],
            [
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]),
                static function (Dummy $dummy): bool {
                    return $dummy->getValue() === 1;
                },
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
            ],
            [
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(1)]),
                static function (Dummy $dummy): bool {
                    return $dummy->getValue() === 1;
                },
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(1)]),
            ],
            [
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]),
                static function (Dummy $dummy): bool {
                    return $dummy->getValue() === 3;
                },
                new DummyGenericList(Dummy::class, []),
            ],
        ];
    }

    /**
     * @param int[] $expectedArray
     *
     * @test
     * @dataProvider provideMap
     */
    public function it_can_map_its_elements(DummyGenericList $list, array $expectedArray): void
    {
        Assert::assertEquals($expectedArray, $list->map(static function (Dummy $dummy): int {
            return $dummy->getValue();
        }));
    }

    public function provideMap(): array
    {
        return [
            [new DummyGenericList(Dummy::class, []), []],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), [1, 2]],
        ];
    }

    /**
     * @test
     */
    public function it_can_walk_every_element(): void
    {
        $array = [new Dummy(1), new Dummy(2)];
        $list = new DummyGenericList(Dummy::class, $array);

        $list->walk(function (Dummy $element) use ($array): void {
            Assert::assertSame($array[$this->callbackCounter], $element);
            $this->callbackCounter++;
        });

        Assert::assertSame(2, $this->callbackCounter);
    }

    /**
     * @test
     * @dataProvider provideMerge
     */
    public function it_can_be_merged_with_another_list(DummyGenericList $listA, DummyGenericList $listB, DummyGenericList $expectedList): void
    {
        Assert::assertEquals($expectedList, $listA->merge($listB));
    }

    public function provideMerge(): array
    {
        return [
            [
                new DummyGenericList(Dummy::class),
                new DummyGenericList(Dummy::class),
                new DummyGenericList(Dummy::class),
            ],
            [
                new DummyGenericList(Dummy::class),
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
            ],
            [
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(1)]),
            ],
            [
                new DummyGenericList(Dummy::class, [new Dummy(1)]),
                new DummyGenericList(Dummy::class, [new Dummy(2)]),
                new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideSlice
     */
    public function it_can_slice_elements(DummyGenericList $list, int $offset, ?int $length, DummyGenericList $expectedList): void
    {
        Assert::assertEquals($expectedList, $list->slice($offset, $length));
    }

    public function provideSlice(): array
    {
        return [
            [new DummyGenericList(Dummy::class), 0, null, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class), 0, 1, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class), 0, 0, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class), 1, 1, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 0, null, new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 0, 0, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 0, 1, new DummyGenericList(Dummy::class, [new Dummy(1)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 0, 2, new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 1, null, new DummyGenericList(Dummy::class, [new Dummy(2)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 1, 0, new DummyGenericList(Dummy::class)],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 1, 1, new DummyGenericList(Dummy::class, [new Dummy(2)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), 1, 2, new DummyGenericList(Dummy::class, [new Dummy(2)])],
        ];
    }

    /**
     * @test
     */
    public function it_can_return_the_only_element(): void
    {
        $list = new DummyGenericList(Dummy::class, [new Dummy(1)]);

        Assert::assertEquals(new Dummy(1), $list->only());
    }

    /**
     * @test
     */
    public function it_can_return_the_only_element_after_filtering(): void
    {
        $list = new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]);

        $filterFunction = static function (Dummy $dummy): bool {
            return $dummy->getValue() === 2;
        };

        Assert::assertEquals(new Dummy(2), $list->filter($filterFunction)->only());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_only_when_no_element_was_found(): void
    {
        $this->expectException(NoElements::class);

        $list = new DummyGenericList(Dummy::class);

        $list->only();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_only_when_more_than_one_element_was_found(): void
    {
        $this->expectException(ElementsAreNotUnique::class);

        $list = new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]);

        $list->only();
    }

    /**
     * @test
     * @dataProvider provideGetIterator
     */
    public function it_exposes_an_iterator(GenericList $list, ArrayIterator $expectedIterator): void
    {
        Assert::assertEquals($expectedIterator, $list->getIterator());
    }

    public function provideGetIterator(): array
    {
        return [
            [new DummyGenericList(Dummy::class), new ArrayIterator()],
            [new DummyGenericList(Dummy::class, [new Dummy(1)]), new ArrayIterator([new Dummy(1)])],
            [new DummyGenericList(Dummy::class, [new Dummy(1), new Dummy(2)]), new ArrayIterator([new Dummy(1), new Dummy(2)])],
        ];
    }
}
