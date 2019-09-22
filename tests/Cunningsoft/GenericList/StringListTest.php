<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use ArrayIterator;
use Cunningsoft\GenericList\StringList;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Dummy;

final class StringListTest extends TestCase
{
    /** @var int */
    private $callbackCounter = 0;

    /**
     * @param string[] $values
     *
     * @test
     * @dataProvider provideConstructor
     */
    public function it_can_be_initialised(array $values): void
    {
        $exceptionThrown = false;

        try {
            new StringList($values);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        Assert::assertFalse($exceptionThrown);
    }

    public function provideConstructor(): array
    {
        return [
            [[]],
            [['foo']],
            [['foo', 'bar']],
        ];
    }

    /**
     * @test
     */
    public function it_can_be_initialised_without_parameters(): void
    {
        $exceptionThrown = false;

        try {
            new StringList();
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        Assert::assertFalse($exceptionThrown);
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

        new StringList(['foo', 'bar', $invalidType]);
    }

    public function provideInvalidType(): array
    {
        return [
            [1],
            [1.5],
            [true],
            [new Dummy(1)],
        ];
    }

    /**
     * @test
     * @dataProvider provideCount
     */
    public function it_can_count_its_elements(StringList $list, int $expectedCount): void
    {
        Assert::assertSame($expectedCount, $list->count());
    }

    public function provideCount(): array
    {
        return [
            [new StringList(), 0],
            [new StringList(['foo']), 1],
            [new StringList(['foo', 'bar']), 2],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsEmpty
     */
    public function it_can_check_if_it_is_empty(StringList $list, bool $expectedIsEmpty): void
    {
        Assert::assertSame($expectedIsEmpty, $list->isEmpty());
    }

    public function provideIsEmpty(): array
    {
        return [
            [new StringList(), true],
            [new StringList(['foo']), false],
            [new StringList(['foo', 'bar']), false],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsNotEmpty
     */
    public function it_can_check_if_it_is_not_empty(StringList $list, bool $expectedIsNotEmpty): void
    {
        Assert::assertSame($expectedIsNotEmpty, $list->isNotEmpty());
    }

    public function provideIsNotEmpty(): array
    {
        return [
            [new StringList(), false],
            [new StringList(['foo']), true],
            [new StringList(['foo', 'bar']), true],
        ];
    }

    /**
     * @test
     * @dataProvider provideMerge
     */
    public function it_can_be_merged_with_another_list(StringList $listA, StringList $listB, StringList $expectedList): void
    {
        Assert::assertEquals($expectedList, $listA->merge($listB));
    }

    public function provideMerge(): array
    {
        return [
            [
                new StringList(),
                new StringList(),
                new StringList(),
            ],
            [
                new StringList(),
                new StringList(['foo']),
                new StringList(['foo']),
            ],
            [
                new StringList(['foo']),
                new StringList(['foo']),
                new StringList(['foo', 'foo']),
            ],
            [
                new StringList(['foo']),
                new StringList(['bar']),
                new StringList(['foo', 'bar']),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideSlice
     */
    public function it_can_slice_elements(StringList $list, int $offset, ?int $length, StringList $expectedList): void
    {
        Assert::assertEquals($expectedList, $list->slice($offset, $length));
    }

    public function provideSlice(): array
    {
        return [
            [new StringList(), 0, null, new StringList()],
            [new StringList(), 0, 1, new StringList()],
            [new StringList(), 0, 0, new StringList()],
            [new StringList(), 1, 1, new StringList()],
            [new StringList(['foo', 'bar']), 0, null, new StringList(['foo', 'bar'])],
            [new StringList(['foo', 'bar']), 0, 0, new StringList()],
            [new StringList(['foo', 'bar']), 0, 1, new StringList(['foo'])],
            [new StringList(['foo', 'bar']), 0, 2, new StringList(['foo', 'bar'])],
            [new StringList(['foo', 'bar']), 1, null, new StringList(['bar'])],
            [new StringList(['foo', 'bar']), 1, 0, new StringList()],
            [new StringList(['foo', 'bar']), 1, 1, new StringList(['bar'])],
            [new StringList(['foo', 'bar']), 1, 2, new StringList(['bar'])],
        ];
    }

    /**
     * @test
     * @dataProvider provideDistinct
     */
    public function it_can_create_a_distinct_list(StringList $list, StringList $expectedList): void
    {
        Assert::assertEquals($expectedList, $list->distinct());
    }

    public function provideDistinct(): array
    {
        return [
            [new StringList(), new StringList()],
            [new StringList(['foo']), new StringList(['foo'])],
            [new StringList(['foo', 'bar']), new StringList(['foo', 'bar'])],
            [new StringList(['foo', 'foo', 'bar']), new StringList(['foo', 'bar'])],
        ];
    }

    /**
     * @test
     */
    public function it_can_walk_every_element(): void
    {
        $array = ['foo', 'bar'];
        $list = new StringList($array);

        $list->walk(function (string $element) use ($array): void {
            Assert::assertSame($array[$this->callbackCounter], $element);
            $this->callbackCounter++;
        });

        Assert::assertSame(2, $this->callbackCounter);
    }

    /**
     * @test
     * @dataProvider provideImplode
     */
    public function it_can_implode_its_elements(StringList $list, string $glue, string $expectedString): void
    {
        Assert::assertSame($expectedString, $list->implode($glue));
    }

    public function provideImplode(): array
    {
        return [
            [new StringList(), '', ''],
            [new StringList(['foo']), '', 'foo'],
            [new StringList(['foo', 'bar']), '', 'foobar'],
            [new StringList(), '-', ''],
            [new StringList(['foo']), '-', 'foo'],
            [new StringList(['foo', 'bar']), '-', 'foo-bar'],
        ];
    }

    /**
     * @test
     * @dataProvider provideGetIterator
     */
    public function it_exposes_an_iterator(StringList $list, ArrayIterator $expectedIterator): void
    {
        Assert::assertEquals($expectedIterator, $list->getIterator());
    }

    public function provideGetIterator(): array
    {
        return [
            [new StringList(), new ArrayIterator()],
            [new StringList(['foo']), new ArrayIterator(['foo'])],
            [new StringList(['foo', 'bar']), new ArrayIterator(['foo', 'bar'])],
        ];
    }
}
