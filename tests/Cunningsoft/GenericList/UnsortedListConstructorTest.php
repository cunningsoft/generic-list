<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Dummy;
use Tests\UnsortedDummyList;

final class UnsortedListConstructorTest extends TestCase
{
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
            new UnsortedDummyList($values);
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

        new UnsortedDummyList([new Dummy(1), new Dummy(2), $invalidType]);
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
}
