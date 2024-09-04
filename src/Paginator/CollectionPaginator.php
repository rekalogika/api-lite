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
use Doctrine\Common\Collections\ReadableCollection;

/**
 * @template T of object
 * @implements PaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final class CollectionPaginator implements \IteratorAggregate, PaginatorInterface
{
    /**
     * @var array<array-key,T>
     */
    private readonly array $items;
    private readonly float $totalItems;

    /**
     * @param ReadableCollection<array-key,T> $collection
     */
    public function __construct(
        readonly ReadableCollection $collection,
        private readonly float $currentPage,
        private readonly float $itemsPerPage,
    ) {
        $this->items = $collection->slice((int) (($currentPage - 1) * $itemsPerPage), $itemsPerPage > 0 ? (int) $itemsPerPage : null);
        $this->totalItems = $collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPage(): float
    {
        if (0. >= $this->itemsPerPage) {
            return 1.;
        }

        return max(ceil($this->totalItems / $this->itemsPerPage) ?: 1., 1.);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * {@inheritdoc}
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
