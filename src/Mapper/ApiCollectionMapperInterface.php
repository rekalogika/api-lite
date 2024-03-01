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

namespace Rekalogika\ApiLite\Mapper;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use Rekalogika\Mapper\Context\Context;

interface ApiCollectionMapperInterface
{
    /**
     * @template TOutput of object
     * @param null|class-string<TOutput> $target
     * @param array<string,mixed> $context
     * @return ($target is null ? PaginatorInterface<object> : PaginatorInterface<TOutput>)
     */
    public function mapCollection(
        object $collection,
        ?string $target,
        Operation $operation,
        array $context = [],
        ?Context $mapperContext = null,
    ): PaginatorInterface;
}
