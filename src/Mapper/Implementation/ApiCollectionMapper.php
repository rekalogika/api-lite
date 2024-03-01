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

namespace Rekalogika\ApiLite\Mapper\Implementation;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface;
use Rekalogika\ApiLite\Mapper\ApiCollectionMapperInterface;
use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\ApiLite\Paginator\MappingPaginatorDecorator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Mapper\Context\Context;

final class ApiCollectionMapper implements ApiCollectionMapperInterface
{
    /**
     * @param PaginatorApplierInterface<object> $paginatorApplier
     */
    public function __construct(
        private ApiMapperInterface $mapper,
        private Pagination $pagination,
        private PaginatorApplierInterface $paginatorApplier,
    ) {
    }

    public function mapCollection(
        object $collection,
        string $target,
        Operation $operation,
        array $context = [],
        ?Context $mapperContext = null
    ): PaginatorInterface {
        if ($collection instanceof PaginatorInterface) {
            $paginator = $collection;
        } else {
            $paginator = $this->paginate($collection, $operation, $context);
        }

        return new MappingPaginatorDecorator(
            paginator: $paginator,
            mapper: $this->mapper,
            targetClass: $target,
            context: $mapperContext,
        );
    }

    /**
     * @param array<string,mixed> $context
     * @return PaginatorInterface<object>
     * @throws UnsupportedObjectException
     */
    private function paginate(
        object $collection,
        Operation $operation,
        array $context
    ): PaginatorInterface {
        [$currentPage,, $itemsPerPage] = $this->getPagination($operation, $context);

        return $this->paginatorApplier
            ->applyPaginator($collection, $currentPage, $itemsPerPage);
    }

    /**
     * @param array<string,mixed> $context
     * @return array{int,int,int}
     */
    private function getPagination(Operation $operation, array $context): array
    {
        /** @var array{int,int,int} */
        return $this->pagination->getPagination($operation, $context);
    }
}
