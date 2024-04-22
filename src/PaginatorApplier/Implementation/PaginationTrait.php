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

trait PaginationTrait
{
    /**
     * @param array<string,mixed> $context
     * @return array{int,int,int}
     */
    private function getPagination(Operation $operation, array $context): array
    {
        /** @var array{int,int,int} */
        return $this->pagination->getPagination($operation, $context);
    }
}
