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
use ApiPlatform\State\Pagination\PaginatorInterface;
use Rekalogika\ApiLite\Mapper\ApiCollectionMapperInterface;
use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\ApiLite\Paginator\MappingPaginatorDecorator;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\ApiLite\Rekapager\MappingPagerDecorator;
use Rekalogika\Mapper\Context\Context;
use Rekalogika\Rekapager\Contracts\TraversablePagerInterface;

final class ApiCollectionMapper implements ApiCollectionMapperInterface
{
    /**
     * @param PaginatorApplierInterface<object> $paginatorApplier
     */
    public function __construct(
        private ApiMapperInterface $mapper,
        private PaginatorApplierInterface $paginatorApplier,
    ) {
    }

    public function mapCollection(
        object $collection,
        ?string $target,
        Operation $operation,
        array $context = [],
        ?Context $mapperContext = null
    ): iterable {
        if ($collection instanceof TraversablePagerInterface) {
            /** @var TraversablePagerInterface<array-key,object> */
            $paginator = $collection;
        } elseif ($collection instanceof PaginatorInterface) {
            /** @var PaginatorInterface<object> $paginator */
            $paginator = $collection;
        } else {
            $paginator = $this->paginate($collection, $operation, $context);
        }

        if ($target === null) {
            return $paginator;
        } elseif ($paginator instanceof TraversablePagerInterface) {
            /**
             * @var TraversablePagerInterface<array-key,object>
             * @psalm-suppress MixedArgumentTypeCoercion
             */
            return new MappingPagerDecorator(
                pager: $paginator,
                mapper: $this->mapper,
                targetClass: $target,
                context: $mapperContext,
            );
        } elseif ($paginator instanceof PaginatorInterface) {
            /** @var PaginatorInterface<object> */
            $result = new MappingPaginatorDecorator(
                paginator: $paginator,
                mapper: $this->mapper,
                targetClass: $target,
                context: $mapperContext,
            );

            return $result;
        }
        throw new \InvalidArgumentException('Invalid target class');

    }

    /**
     * @param array<string,mixed> $context
     * @return iterable<object>
     * @throws UnsupportedObjectException
     */
    private function paginate(
        object $collection,
        Operation $operation,
        array $context
    ): iterable {
        return $this->paginatorApplier
            ->applyPaginator($collection, $operation, $context);
    }
}
