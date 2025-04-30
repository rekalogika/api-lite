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
        $itemsPerPage = (int) $itemsPerPage;
        $currentPage = (int) $currentPage;

        $this->items = $collection->slice(
            offset: ($currentPage - 1) * $itemsPerPage,
            length: $itemsPerPage > 0 ? $itemsPerPage : null,
        );

        $this->totalItems = $collection->count();
    }

    #[\Override]
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    #[\Override]
    public function getLastPage(): float
    {
        if (0. >= $this->itemsPerPage) {
            return 1.;
        }

        return max(ceil($this->totalItems / $this->itemsPerPage) ?: 1., 1.);
    }

    #[\Override]
    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    #[\Override]
    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return \Traversable<T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }
}
