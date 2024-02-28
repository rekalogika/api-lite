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

namespace Rekalogika\ApiLite\PaginatorApplier;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Rekalogika\ApiLite\PaginatorApplier\Exception\UnsupportedObjectException;

/**
 * @template TOutputMember of object
 */
interface PaginatorApplierInterface
{
    /**
     * @return PaginatorInterface<TOutputMember>
     * @throws UnsupportedObjectException
     */
    public function applyPaginator(
        object $object,
        int $currentPage,
        int $itemsPerPage
    ): PaginatorInterface;
}
