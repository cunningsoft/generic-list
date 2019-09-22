<?php

declare(strict_types=1);

namespace Tests\Cunningsoft\GenericList;

use Cunningsoft\GenericList\NoElements;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class NoElementsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $exception = new NoElements();

        Assert::assertSame('No element was found in the list.', $exception->getMessage());
    }
}
