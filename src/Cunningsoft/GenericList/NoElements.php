<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use LogicException;

final class NoElements extends LogicException
{
    public function __construct()
    {
        parent::__construct('No element was found in the list.');
    }
}
