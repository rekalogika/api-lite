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

use ApiPlatform\State\Pagination\PaginatorInterface;
use Doctrine\Common\Collections\ReadableCollection;
use Rekalogika\ApiLite\Paginator\CollectionPaginator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class CollectionPaginatorApplier implements PaginatorApplierInterface
{
    public function applyPaginator(
        object $object,
        int $currentPage,
        int $itemsPerPage
    ): PaginatorInterface {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!$object instanceof ReadableCollection) {
            /** @psalm-suppress NoValue */
            throw new UnsupportedObjectException($this, $object);
        }

        /**
         * @var ReadableCollection<array-key,TOutputMember> $object
         */

        return new CollectionPaginator($object, $currentPage, $itemsPerPage);
    }
}
