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
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Rekalogika\ApiLite\Paginator\PagerfantaPaginator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class PagerfantaPaginatorApplier implements PaginatorApplierInterface
{
    use PaginationTrait;

    public function __construct(private Pagination $pagination) {}

    public function applyPaginator(
        object $object,
        Operation $operation,
        array $context,
    ): iterable {
        if ($object instanceof AdapterInterface) {
            $object = new Pagerfanta($object);
        }

        if (!$object instanceof PagerfantaInterface) {
            throw new UnsupportedObjectException($this, $object);
        }

        [$currentPage,, $itemsPerPage] = $this->getPagination($operation, $context);

        /** @var PagerfantaInterface<TOutputMember> $object */

        $object->setMaxPerPage($itemsPerPage);
        $object->setCurrentPage($currentPage);

        return new PagerfantaPaginator($object);
    }
}
