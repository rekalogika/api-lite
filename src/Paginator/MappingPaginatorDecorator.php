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

namespace Rekalogika\ApiLite\Paginator;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\Mapper\Context\Context;

/**
 * @template T of object
 * @implements PaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final class MappingPaginatorDecorator implements \IteratorAggregate, PaginatorInterface
{
    /**
     * @param PaginatorInterface<object> $paginator
     * @param class-string<T> $targetClass
     */
    public function __construct(
        private PaginatorInterface $paginator,
        private ApiMapperInterface $mapper,
        private string $targetClass,
        private ?Context $context = null,
    ) {}

    /**
     * @return \Traversable<T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        foreach ($this->paginator as $item) {
            yield  $this->mapper->map($item, $this->targetClass, $this->context);
        }
    }

    #[\Override]
    public function getLastPage(): float
    {
        return $this->paginator->getLastPage();
    }

    #[\Override]
    public function getTotalItems(): float
    {
        return $this->paginator->getTotalItems();
    }

    #[\Override]
    public function getCurrentPage(): float
    {
        return $this->paginator->getCurrentPage();
    }

    #[\Override]
    public function getItemsPerPage(): float
    {
        return $this->paginator->getItemsPerPage();
    }

    #[\Override]
    public function count(): int
    {
        return $this->paginator->count();
    }
}
