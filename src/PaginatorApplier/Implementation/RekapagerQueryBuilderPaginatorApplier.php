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
use Doctrine\ORM\QueryBuilder;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Rekapager\ApiPlatform\PagerFactoryInterface;
use Rekalogika\Rekapager\Doctrine\ORM\QueryBuilderAdapter;
use Rekalogika\Rekapager\Keyset\KeysetPageable;

/**
 * @template TOutputMember of object
 * @implements PaginatorApplierInterface<TOutputMember>
 */
class RekapagerQueryBuilderPaginatorApplier implements PaginatorApplierInterface
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

        /** @var bool|'keyset'|'offset' */
        $isEnabled = $extraProperties['api_lite_rekapager'] ?? false;

        if (!$isEnabled) {
            throw new UnsupportedObjectException($this, $object);
        }

        if (!$object instanceof QueryBuilder) {
            throw new UnsupportedObjectException($this, $object);
        }

        $adapter = new QueryBuilderAdapter($object);
        /** @var KeysetPageable<array-key,TOutputMember> */
        $pageable = new KeysetPageable($adapter);
        $pager = $this->pagerFactory->createPager($pageable, $operation, $context);

        return $pager;
    }
}
