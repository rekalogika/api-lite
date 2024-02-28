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

use ApiPlatform\State\Pagination\PartialPaginatorInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\Common\Collections\Selectable;

/**
 * @template T of object
 * @implements PartialPaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final readonly class SelectablePartialPaginator implements \IteratorAggregate, PartialPaginatorInterface
{
    /**
     * @var ReadableCollection<array-key,T>
     */
    private readonly ReadableCollection $slicedCollection;

    /**
     * @param Selectable<array-key,T> $selectable
     */
    public function __construct(
        readonly Selectable $selectable,
        private readonly float $currentPage,
        private readonly float $itemsPerPage
    ) {
        $criteria = Criteria::create()
            ->setFirstResult((int) (($currentPage - 1) * $itemsPerPage))
            ->setMaxResults((int) $itemsPerPage);

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
    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
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
