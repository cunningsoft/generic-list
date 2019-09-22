<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use Cunningsoft\GenericList\ElementsAreNotUnique;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class ElementsAreNotUniqueTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $exception = new ElementsAreNotUnique();

        Assert::assertSame('The elements in this list are not unique.', $exception->getMessage());
    }
}
