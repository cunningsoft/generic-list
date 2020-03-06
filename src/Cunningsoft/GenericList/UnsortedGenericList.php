<?php

declare(strict_types=1);

namespace Cunningsoft\GenericList;

/**
 * This class must only be used in *List implementations. It provides a layer on top of php's array to ease
 * the creation of concrete list classes like UserList or BlogPostList.
 *
 * @template T2
 * @extends GenericList<T2>
 */
final class UnsortedGenericList extends GenericList
{
}
