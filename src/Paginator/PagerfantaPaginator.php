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
use Pagerfanta\PagerfantaInterface;

/**
 * @template T of object
 * @implements PaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final class PagerfantaPaginator implements \IteratorAggregate, PaginatorInterface
{
    /**
     * @param PagerfantaInterface<T> $pagerfanta
     */
    public function __construct(
        private readonly PagerfantaInterface $pagerfanta,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage(): float
    {
        return $this->pagerfanta->getCurrentPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPage(): float
    {
        return $this->pagerfanta->getNbPages();
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsPerPage(): float
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalItems(): float
    {
        return $this->pagerfanta->getNbResults();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->pagerfanta->count();
    }

    /**
     * {@inheritdoc}
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        return $this->pagerfanta->getIterator();
    }
}
