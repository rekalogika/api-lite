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
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\Common\Collections\Selectable;

/**
 * @template T of object
 * @implements PaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final class SelectablePaginator implements \IteratorAggregate, PaginatorInterface
{
    /**
     * @var ReadableCollection<array-key,T>
     */
    private readonly ReadableCollection $slicedCollection;
    private readonly float $totalItems;

    /**
     * @param Selectable<array-key,T> $selectable
     */
    public function __construct(
        readonly Selectable $selectable,
        private readonly float $currentPage,
        private readonly float $itemsPerPage,
    ) {
        $this->totalItems = $this->selectable instanceof \Countable ? $this->selectable->count() : $this->selectable->matching(Criteria::create())->count();

        $criteria = Criteria::create()
            ->setFirstResult((int) (($currentPage - 1) * $itemsPerPage))
            ->setMaxResults($itemsPerPage > 0 ? (int) $itemsPerPage : null);

        $this->slicedCollection = $selectable->matching($criteria);
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
        return $this->slicedCollection->count();
    }

    /**
     * {@inheritdoc}
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        return $this->slicedCollection->getIterator();
    }
}
