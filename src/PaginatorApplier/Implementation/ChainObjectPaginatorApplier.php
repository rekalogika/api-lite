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

namespace Rekalogika\ApiLite\PaginatorApplier\Implementation;

use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

/**
 * @implements PaginatorApplierInterface<object>
 */
class ChainObjectPaginatorApplier implements PaginatorApplierInterface
{
    /**
     * @param iterable<PaginatorApplierInterface<object>> $objectPaginators
     */
    public function __construct(
        private iterable $objectPaginators
    ) {
    }

    public function applyPaginator(
        object $object,
        int $currentPage,
        int $itemsPerPage
    ): iterable {
        foreach ($this->objectPaginators as $objectPaginator) {
            try {
                return $objectPaginator->applyPaginator($object, $currentPage, $itemsPerPage);
            } catch (UnsupportedObjectException) {
                continue;
            }
        }

        throw new UnsupportedObjectException();
    }
}
