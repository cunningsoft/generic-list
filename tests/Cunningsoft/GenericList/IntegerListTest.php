<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use ArrayIterator;
use Cunningsoft\GenericList\IntegerList;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Dummy;

final class IntegerListTest extends TestCase
{
    /**
     * @param int[] $values
     *
     * @test
     * @dataProvider provideConstructor
     */
    public function it_can_be_initialised(array $values): void
    {
        $exceptionThrown = false;

        try {
            new IntegerList($values);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        Assert::assertFalse($exceptionThrown);
    }

    public function provideConstructor(): array
    {
        return [
            [[]],
            [[1]],
            [[1, 2]],
        ];
    }

    /**
     * @test
     */
    public function it_can_be_initialised_without_parameters(): void
    {
        $exceptionThrown = false;

        try {
            new IntegerList();
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

        new IntegerList([1, 2, $invalidType]);
    }

    public function provideInvalidType(): array
    {
        return [
            ['foo'],
            [1.5],
            [true],
            [new Dummy(1)],
        ];
    }

    /**
     * @test
     * @dataProvider provideCount
     */
    public function it_can_count_its_elements(IntegerList $list, int $expectedCount): void
    {
        Assert::assertSame($expectedCount, $list->count());
    }

    public function provideCount(): array
    {
        return [
            [new IntegerList(), 0],
            [new IntegerList([1]), 1],
            [new IntegerList([1, 2]), 2],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsEmpty
     */
    public function it_can_check_if_it_is_empty(IntegerList $list, bool $expectedIsEmpty): void
    {
        Assert::assertSame($expectedIsEmpty, $list->isEmpty());
    }

    public function provideIsEmpty(): array
    {
        return [
            [new IntegerList(), true],
            [new IntegerList([1]), false],
            [new IntegerList([1, 2]), false],
        ];
    }

    /**
     * @test
     * @dataProvider provideIsNotEmpty
     */
    public function it_can_check_if_it_is_not_empty(IntegerList $list, bool $expectedIsNotEmpty): void
    {
        Assert::assertSame($expectedIsNotEmpty, $list->isNotEmpty());
    }

    public function provideIsNotEmpty(): array
    {
        return [
            [new IntegerList(), false],
            [new IntegerList([1]), true],
            [new IntegerList([1, 2]), true],
        ];
    }

    /**
     * @test
     * @dataProvider provideSum
     */
    public function it_can_calculate_the_sum(IntegerList $list, int $expectedSum): void
    {
        Assert::assertSame($expectedSum, $list->sum());
    }

    public function provideSum(): array
    {
        return [
            [new IntegerList(), 0],
            [new IntegerList([1, 2, 3]), 6],
        ];
    }

    /**
     * @test
     * @dataProvider provideAverage
     */
    public function it_can_calculate_the_average(IntegerList $list, float $expectedAverage): void
    {
        Assert::assertSame($expectedAverage, $list->average());
    }

    public function provideAverage(): array
    {
        return [
            [new IntegerList(), 0.0],
            [new IntegerList([1, 2, 3]), 2.0],
            [new IntegerList([1, 2, 3, 4]), 2.5],
        ];
    }

    /**
     * @test
     * @dataProvider provideGetIterator
     */
    public function it_exposes_an_iterator(IntegerList $list, ArrayIterator $expectedIterator): void
    {
        Assert::assertEquals($expectedIterator, $list->getIterator());
    }

    public function provideGetIterator(): array
    {
        return [
            [new IntegerList(), new ArrayIterator()],
            [new IntegerList([1]), new ArrayIterator([1])],
            [new IntegerList([1, 2]), new ArrayIterator([1, 2])],
        ];
    }
}
