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

use Doctrine\Common\Collections\Selectable;
use Rekalogika\ApiLite\Paginator\SelectablePaginator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class SelectablePaginatorApplier implements PaginatorApplierInterface
{
    public function applyPaginator(
        object $object,
        int $currentPage,
        int $itemsPerPage
    ): iterable {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!$object instanceof Selectable) {
            /** @psalm-suppress NoValue */
            throw new UnsupportedObjectException($this, $object);
        }

        /**
         * @var Selectable<array-key,TOutputMember> $object
         */

        return new SelectablePaginator($object, $currentPage, $itemsPerPage);
    }
}
