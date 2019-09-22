<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

use LogicException;

final class ElementsAreNotUnique extends LogicException
{
    public function __construct()
    {
        parent::__construct('The elements in this list are not unique.');
    }
}
