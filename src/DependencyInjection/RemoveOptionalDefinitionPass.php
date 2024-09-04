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

namespace Rekalogika\ApiLite\DependencyInjection;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Pagerfanta\PagerfantaInterface;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\CollectionPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\DoctrineOrmPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\PagerfantaPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\SelectablePaginatorApplier;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
final readonly class RemoveOptionalDefinitionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!interface_exists(PagerfantaInterface::class)) {
            $container->removeDefinition(PagerfantaPaginatorApplier::class);
        }

        if (!interface_exists(Collection::class)) {
            $container->removeDefinition(CollectionPaginatorApplier::class);
            $container->removeDefinition(SelectablePaginatorApplier::class);
        }

        if (!class_exists(Query::class)) {
            $container->removeDefinition(DoctrineOrmPaginatorApplier::class);
        }
    }
}
