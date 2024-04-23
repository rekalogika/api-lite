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

namespace Rekalogika\ApiLite\Rekapager;

use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\Mapper\Context\Context;
use Rekalogika\Rekapager\Contracts\PagerItemInterface;
use Rekalogika\Rekapager\Contracts\TraversablePagerInterface;

/**
 * @template TKey of array-key
 * @template T of object
 * @template TIdentifier of object
 * @implements TraversablePagerInterface<TKey,T,TIdentifier>
 * @implements \IteratorAggregate<TKey,T>
 */
final class MappingPagerDecorator implements \IteratorAggregate, TraversablePagerInterface
{
    /**
     * @param TraversablePagerInterface<TKey,object,TIdentifier> $pager
     * @param class-string<T> $targetClass
     */
    public function __construct(
        private TraversablePagerInterface $pager,
        private ApiMapperInterface $mapper,
        private string $targetClass,
        private ?Context $context = null,
    ) {
    }

    public function getProximity(): int
    {
        return $this->pager->getProximity();
    }

    public function withProximity(int $proximity): static
    {
        /** @var TraversablePagerInterface<TKey,T,TIdentifier> */
        $pager = $this->pager->withProximity($proximity);

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return new self(
            $pager,
            $this->mapper,
            $this->targetClass,
            $this->context
        );
    }

    public function getCurrentPage(): PagerItemInterface
    {
        return new MappingPagerItemDecorator(
            $this->pager->getCurrentPage(),
            $this->mapper,
            $this->targetClass
        );
    }

    public function getPreviousPage(): ?PagerItemInterface
    {
        $page = $this->pager->getPreviousPage();

        if ($page === null) {
            return null;
        }

        return new MappingPagerItemDecorator(
            $page,
            $this->mapper,
            $this->targetClass
        );
    }

    public function getNextPage(): ?PagerItemInterface
    {
        $page = $this->pager->getNextPage();

        if ($page === null) {
            return null;
        }

        return new MappingPagerItemDecorator(
            $page,
            $this->mapper,
            $this->targetClass
        );
    }

    public function getFirstPage(): ?PagerItemInterface
    {
        $page = $this->pager->getFirstPage();

        if ($page === null) {
            return null;
        }

        return new MappingPagerItemDecorator(
            $page,
            $this->mapper,
            $this->targetClass
        );
    }

    public function getLastPage(): ?PagerItemInterface
    {
        $page = $this->pager->getLastPage();

        if ($page === null) {
            return null;
        }

        return new MappingPagerItemDecorator(
            $page,
            $this->mapper,
            $this->targetClass
        );
    }

    public function hasGapToFirstPage(): bool
    {
        return $this->pager->hasGapToFirstPage();
    }

    public function hasGapToLastPage(): bool
    {
        return $this->pager->hasGapToLastPage();
    }

    public function getPreviousNeighboringPages(): iterable
    {
        foreach ($this->pager->getPreviousNeighboringPages() as $page) {
            yield new MappingPagerItemDecorator(
                $page,
                $this->mapper,
                $this->targetClass
            );
        }
    }

    public function getNextNeighboringPages(): iterable
    {
        foreach ($this->pager->getNextNeighboringPages() as $page) {
            yield new MappingPagerItemDecorator(
                $page,
                $this->mapper,
                $this->targetClass
            );
        }
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->pager as $key => $item) {
            yield $key => $this->mapper->map($item, $this->targetClass, $this->context);
        }
    }
}
