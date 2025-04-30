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

    #[\Override]
    public function getUrl(): ?string
    {
        return $this->pagerItem->getUrl();
    }

    #[\Override]
    public function isDisabled(): bool
    {
        return $this->pagerItem->isDisabled();
    }

    #[\Override]
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

    #[\Override]
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

    #[\Override]
    public function getNextPages(int $numberOfPages): array
    {
        $nextPages = $this->pagerItem->getNextPages($numberOfPages);

        return array_map(
            fn(PagerItemInterface $pagerItem) => new self($pagerItem, $this->mapper, $this->targetClass),
            $nextPages,
        );
    }

    #[\Override]
    public function getPreviousPages(int $numberOfPages): array
    {
        $previousPages = $this->pagerItem->getPreviousPages($numberOfPages);

        return array_map(
            fn(PagerItemInterface $pagerItem) => new self($pagerItem, $this->mapper, $this->targetClass),
            $previousPages,
        );
    }

    #[\Override]
    public function getPageIdentifier(): object
    {
        return $this->pagerItem->getPageIdentifier();
    }

    #[\Override]
    public function getPageNumber(): ?int
    {
        return $this->pagerItem->getPageNumber();
    }

    #[\Override]
    public function withPageNumber(?int $pageNumber): static
    {
        throw new \BadMethodCallException('Not implemented');
    }

    #[\Override]
    public function getPageable(): PageableInterface
    {
        throw new \BadMethodCallException('Not implemented');
    }

    #[\Override]
    public function getItemsPerPage(): int
    {
        return $this->pagerItem->getItemsPerPage();
    }

    #[\Override]
    public function count(): int
    {
        return $this->pagerItem->count();
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        foreach ($this->pagerItem as $key => $item) {
            yield $key => $this->mapper->map($item, $this->targetClass);
        }
    }
}
