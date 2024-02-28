<?php

declare(strict_types=1);

/*
 * This file is part of rekalogika/api-lite package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\ApiLite\PaginatorApplier\Exception;

use Rekalogika\ApiLite\Exception\UnexpectedValueException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

class UnsupportedObjectException extends UnexpectedValueException
{
    /**
     * @template T of object
     * @param PaginatorApplierInterface<T>|null $paginatorApplier
     * @param object|null $object
     */
    public function __construct(
        ?PaginatorApplierInterface $paginatorApplier = null,
        ?object $object = null,
    ) {
        if ($paginatorApplier !== null && $object !== null) {
            parent::__construct(sprintf(
                'Cannot apply paginator to object of type "%s" using "%s"',
                get_class($object),
                get_class($paginatorApplier)
            ));
        }
    }
}
