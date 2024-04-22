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

use ApiPlatform\Doctrine\Orm\Paginator as OrmPaginator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class DoctrineOrmPaginatorApplier implements PaginatorApplierInterface
{
    use PaginationTrait;

    public function __construct(private Pagination $pagination)
    {
    }

    public function applyPaginator(
        object $object,
        Operation $operation,
        array $context,
    ): iterable {
        if (
            !$object instanceof Query
            && !$object instanceof QueryBuilder
        ) {
            throw new UnsupportedObjectException($this, $object);
        }

        [$currentPage,, $itemsPerPage] = $this->getPagination($operation, $context);

        $object->setFirstResult(($currentPage - 1) * $itemsPerPage);
        $object->setMaxResults($itemsPerPage);

        $paginator = new Paginator($object);

        /** @var PaginatorInterface<TOutputMember> */
        return new OrmPaginator($paginator);
    }
}
