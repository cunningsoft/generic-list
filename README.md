# Generic List

This library provides the tools needed to create custom iterables without the need to repeat all the boilerplate code.

[![Minimum PHP version: 7.2.9](https://img.shields.io/badge/php-7.3%2B-blue.svg)](https://packagist.org/packages/cunningsoft/generic-list)
[![Build Status](https://travis-ci.org/cunningsoft/generic-list.svg?branch=master)](https://travis-ci.org/cunningsoft/generic-list)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/cunningsoft/generic-list/master)](https://github.com/cunningsoft/generic-list)

## Installation

Install the library via [composer](https://getcomposer.org/):

```sh
php composer.phar require cunningsoft/generic-list
```

## Usage

### Unsorted Lists

Create a custom list class (for example for Users):
```php
<?php

declare(strict_types=1);

namespace MyApp;

use ArrayIterator;
use Cunningsoft\GenericList\ListConstructor;
use Cunningsoft\GenericList\ListType;

final class UserList implements ListType
{
    use UnsortedListConstructor;

    public function getIterator(): ArrayIterator
    {
        return $this->list->getIterator();
    }

    protected function getElementType(): string
    {
        return User::class;
    }
}
```

Add common functionality by passing the method call down to the generic list:
```php
    public function isEmpty(): self
    {
        return $this->list->isEmpty();
    }
```

Add functionality to customize the list to the specific needs:
```php
    public function filterOnlyActive(): self
    {
        return new self($this->list->filter(static function (User $user): bool {
            return $user->isActive();
        });
    }
```

Now you can use it like this:
```php
$users = new UserList([$userA, $userB, $userC]);
if ($users->filterOnlyActive()->isEmpty()) {
    echo 'No active users!';
}
```

### Sorted Lists

Sorted lists work the same way as unsorted lists, but they use the `SortedListConstructor` trait instead and because of that you have to provide a sorting function.
```php
<?php

declare(strict_types=1);

namespace MyApp;

use ArrayIterator;
use Cunningsoft\GenericList\ListConstructor;
use Cunningsoft\GenericList\ListType;

final class SortedByAgeUserList implements ListType
{
    use SortedListConstructor;

    public function getIterator(): ArrayIterator
    {
        return $this->list->getIterator();
    }

    protected function getElementType(): string
    {
        return User::class;
    }

    protected function getSortFunction(): callable
    {
        return static function (User $userA, User $userB): int {
            return $userA->getAge() <=> $userB->getAge();
        };
    }
}
```

Now you can use this class in the same way as described above for the unsorted list, but by passing this type of object around you can be sure that it is always in the intended ordered state.

## FAQ

Q: Why not simply use arrays?

A: You get type safety and can assign custom behaviour to each list.

---

Q: Why not simply extend the `GenericList`, `UnsortedGenericList` or `SortedGenericList`?

A: This would create a huge inheritance tree, without any real benefit. This way for every list the exposed behaviour is exactly what it needed for every use case.

## Contact

GitHub: https://github.com/dmecke

Twitter: [@danielmecke](https://twitter.com/danielmecke)
