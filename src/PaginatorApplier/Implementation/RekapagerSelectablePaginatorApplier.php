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
use Doctrine\Common\Collections\Selectable;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Rekapager\ApiPlatform\PagerFactoryInterface;
use Rekalogika\Rekapager\Doctrine\Collections\SelectableAdapter;
use Rekalogika\Rekapager\Keyset\KeysetPageable;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class RekapagerSelectablePaginatorApplier implements PaginatorApplierInterface
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
        /** @psalm-suppress InternalMethod */
        $extraProperties = $operation->getExtraProperties() ?? [];

        /** @var bool */
        $isEnabled = $extraProperties['api_lite_rekapager'] ?? false;

        if (!$isEnabled) {
            throw new UnsupportedObjectException($this, $object);
        }

        /** @psalm-suppress DocblockTypeContradiction */
        if (!$object instanceof Selectable) {
            /** @psalm-suppress NoValue */
            throw new UnsupportedObjectException($this, $object);
        }

        $adapter = new SelectableAdapter($object);
        /** @var KeysetPageable<array-key,TOutputMember> */
        $pageable = new KeysetPageable($adapter);
        $pager = $this->pagerFactory->createPager($pageable, $operation, $context);

        return $pager;
    }
}
