<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use Cunningsoft\GenericList\NoElementAtIndex;
use Cunningsoft\GenericList\NoElements;
use Cunningsoft\GenericList\SortedGenericList;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Dummy;

final class SortedGenericListTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_sort_its_elements(): void
    {
        $values = [new Dummy(2), new Dummy(3), new Dummy(1)];

        $list = new SortedGenericList(Dummy::class, $values);
        $list->sort(static function (Dummy $dummyA, Dummy $dummyB): int {
            return $dummyA->getValue() <=> $dummyB->getValue();
        });

        Assert::assertEquals(new SortedGenericList(Dummy::class, [new Dummy(1), new Dummy(2), new Dummy(3)]), $list);
    }

    /**
     * @test
     * @dataProvider provideAtIndex
     */
    public function it_can_return_an_element_at_a_specific_index(SortedGenericList $list, int $index, Dummy $expectedElement): void
    {
        Assert::assertEquals($expectedElement, $list->atIndex($index));
    }

    public function provideAtIndex(): array
    {
        return [
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                0,
                new Dummy(2),
            ],
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                1,
                new Dummy(3),
            ],
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                2,
                new Dummy(1),
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_return_an_element_at_a_specific_index_after_filtering(): void
    {
        $list = new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]);

        $filterFunction = static function (Dummy $dummy): bool {
            return $dummy->getValue() === 3;
        };

        $filteredList = new SortedGenericList(Dummy::class, $list->filter($filterFunction));

        Assert::assertEquals(new Dummy(3), $filteredList->atIndex(0));
    }

    /**
     * @test
     * @dataProvider provideAtIndex
     */
    public function it_fails_when_accessing_an_element_at_an_invalid_index(): void
    {
        $this->expectException(NoElementAtIndex::class);

        $list = new SortedGenericList(Dummy::class, [new Dummy(1)]);
        $list->atIndex(1);
    }

    /**
     * @test
     * @dataProvider provideAtIndexOrNull
     */
    public function it_can_return_an_element_or_null_at_a_specific_index(SortedGenericList $list, int $index, ?Dummy $expectedElement): void
    {
        Assert::assertEquals($expectedElement, $list->atIndexOrNull($index));
    }

    public function provideAtIndexOrNull(): array
    {
        return [
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                0,
                new Dummy(2),
            ],
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                1,
                new Dummy(3),
            ],
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                2,
                new Dummy(1),
            ],
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                3,
                null,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_return_the_first_element(): void
    {
        $list = new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]);

        Assert::assertEquals(new Dummy(2), $list->first());
    }

    /**
     * @test
     */
    public function it_fails_on_accessing_the_first_element_on_an_empty_list(): void
    {
        $this->expectException(NoElements::class);

        $list = new SortedGenericList(Dummy::class);
        $list->first();
    }

    /**
     * @test
     * @dataProvider provideFirstOrNull
     */
    public function it_can_return_null_as_first_if_no_element_exists(SortedGenericList $list, ?Dummy $expectedElement): void
    {
        Assert::assertEquals($expectedElement, $list->firstOrNull());
    }

    public function provideFirstOrNull(): array
    {
        return [
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                new Dummy(2),
            ],
            [
                new SortedGenericList(Dummy::class),
                null,
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_return_the_last_element(): void
    {
        $list = new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]);

        Assert::assertEquals(new Dummy(1), $list->last());
    }

    /**
     * @test
     */
    public function it_fails_on_accessing_the_last_element_on_an_empty_list(): void
    {
        $this->expectException(NoElements::class);

        $list = new SortedGenericList(Dummy::class);
        $list->last();
    }

    /**
     * @test
     * @dataProvider provideLastOrNull
     */
    public function it_can_return_null_as_last_if_no_element_exists(SortedGenericList $list, ?Dummy $expectedElement): void
    {
        Assert::assertEquals($expectedElement, $list->lastOrNull());
    }

    public function provideLastOrNull(): array
    {
        return [
            [
                new SortedGenericList(Dummy::class, [new Dummy(2), new Dummy(3), new Dummy(1)]),
                new Dummy(1),
            ],
            [
                new SortedGenericList(Dummy::class),
                null,
            ],
        ];
    }
}
