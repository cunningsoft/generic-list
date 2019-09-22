<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use LogicException;
use function sprintf;

final class NoElementAtIndex extends LogicException
{
    public function __construct(int $index)
    {
        parent::__construct(sprintf('No element was found at index %d.', $index));
    }
}
