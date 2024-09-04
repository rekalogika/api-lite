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

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
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
    use PaginationTrait;

    public function __construct(private Pagination $pagination) {}

    public function applyPaginator(
        object $object,
        Operation $operation,
        array $context,
    ): iterable {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!$object instanceof Selectable) {
            /** @psalm-suppress NoValue */
            throw new UnsupportedObjectException($this, $object);
        }

        [$currentPage,, $itemsPerPage] = $this->getPagination($operation, $context);

        /**
         * @var Selectable<array-key,TOutputMember> $object
         */

        return new SelectablePaginator($object, $currentPage, $itemsPerPage);
    }
}
