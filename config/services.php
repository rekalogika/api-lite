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

use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\ApiLite\Mapper\Implementation\ApiMapper;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\ChainObjectPaginatorApplier as ChainPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\CollectionPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\DoctrineOrmPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\SelectablePaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Mapper\MapperInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set('rekalogika.api_lite.paginator_applier.chain', ChainPaginatorApplier::class)
        ->args([
            tagged_iterator('rekalogika.api_lite.paginator_applier')
        ]);

    $services
        ->set(CollectionPaginatorApplier::class)
        ->tag('rekalogika.api_lite.paginator_applier');

    $services
        ->set(SelectablePaginatorApplier::class)
        ->tag('rekalogika.api_lite.paginator_applier');

    $services
        ->set(DoctrineOrmPaginatorApplier::class)
        ->tag('rekalogika.api_lite.paginator_applier');

    $services->alias(PaginatorApplierInterface::class, 'rekalogika.api_lite.paginator_applier.chain');

    $services
        ->set(ApiMapperInterface::class, ApiMapper::class)
        ->args([
            '$mapper' => service(MapperInterface::class),
            '$objectCacheFactory' => service('rekalogika.mapper.object_cache_factory')
        ]);
};
