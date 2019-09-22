<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use Cunningsoft\GenericList\NoElementAtIndex;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class NoElementAtIndexTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $exception = new NoElementAtIndex(1);

        Assert::assertSame('No element was found at index 1.', $exception->getMessage());
    }
}
