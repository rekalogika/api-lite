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
use Rekalogika\Contracts\Rekapager\PageableInterface;
use Rekalogika\Rekapager\Contracts\PagerItemInterface;

/**
 * @template TKey of array-key
 * @template T of object
 * @template TIdentifier of object
 * @implements PagerItemInterface<TKey,T>
 * @implements \IteratorAggregate<TKey,T>
 */
final class MappingPagerItemDecorator implements PagerItemInterface, \IteratorAggregate
{
    /**
     * @param PagerItemInterface<TKey,object> $pagerItem
     * @param ApiMapperInterface $mapper
     * @param class-string<T> $targetClass
     */
    public function __construct(
        private PagerItemInterface $pagerItem,
        private ApiMapperInterface $mapper,
        private string $targetClass,
    ) {}

    public function getUrl(): ?string
    {
        return $this->pagerItem->getUrl();
    }

    public function isDisabled(): bool
    {
        return $this->pagerItem->isDisabled();
    }

    public function getNextPage(): ?PagerItemInterface
    {
        $nextPage = $this->pagerItem->getNextPage();

        if ($nextPage === null) {
            return null;
        }

        return new self(
            $nextPage,
            $this->mapper,
            $this->targetClass,
        );
    }

    public function getPreviousPage(): ?PagerItemInterface
    {
        $previousPage = $this->pagerItem->getPreviousPage();

        if ($previousPage === null) {
            return null;
        }

        return new self(
            $previousPage,
            $this->mapper,
            $this->targetClass,
        );
    }

    public function getNextPages(int $numberOfPages): array
    {
        $nextPages = $this->pagerItem->getNextPages($numberOfPages);

        return array_map(
            fn(PagerItemInterface $pagerItem) => new self($pagerItem, $this->mapper, $this->targetClass),
            $nextPages,
        );
    }

    public function getPreviousPages(int $numberOfPages): array
    {
        $previousPages = $this->pagerItem->getPreviousPages($numberOfPages);

        return array_map(
            fn(PagerItemInterface $pagerItem) => new self($pagerItem, $this->mapper, $this->targetClass),
            $previousPages,
        );
    }

    public function getPageIdentifier(): object
    {
        return $this->pagerItem->getPageIdentifier();
    }

    public function getPageNumber(): ?int
    {
        return $this->pagerItem->getPageNumber();
    }

    public function withPageNumber(?int $pageNumber): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getPageable(): PageableInterface
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function getItemsPerPage(): int
    {
        return $this->pagerItem->getItemsPerPage();
    }

    public function count(): int
    {
        return $this->pagerItem->count();
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->pagerItem as $key => $item) {
            yield $key => $this->mapper->map($item, $this->targetClass);
        }
    }
}
