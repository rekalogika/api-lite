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

namespace Rekalogika\ApiLite\PaginatorApplier\Implementation;

use ApiPlatform\Metadata\Operation;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Contracts\Rekapager\PageableInterface;
use Rekalogika\Rekapager\ApiPlatform\PagerFactoryInterface;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class RekapagerPageablePaginatorApplier implements PaginatorApplierInterface
{
    public function __construct(
        private PagerFactoryInterface $pagerFactory,
    ) {
    }

    public function applyPaginator(
        object $object,
        Operation $operation,
        array $context,
    ): iterable {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!$object instanceof PageableInterface) {
            /** @psalm-suppress NoValue */
            throw new UnsupportedObjectException($this, $object);
        }

        /** @var PageableInterface<array-key,TOutputMember>  $object */

        $pager = $this->pagerFactory->createPager($object, $operation, $context);

        return $pager;
    }
}
