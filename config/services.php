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

use ApiPlatform\State\Pagination\Pagination;
use Rekalogika\ApiLite\Mapper\ApiCollectionMapperInterface;
use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\ApiLite\Mapper\Implementation\ApiCollectionMapper;
use Rekalogika\ApiLite\Mapper\Implementation\ApiMapper;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\ChainObjectPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\CollectionPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\DoctrineOrmPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\RekapagerQueryBuilderPaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\RekapagerSelectablePaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\Implementation\SelectablePaginatorApplier;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Rekalogika\Mapper\MapperInterface;
use Rekalogika\Rekapager\ApiPlatform\PagerFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->set('rekalogika.api_lite.paginator_applier.chain', ChainObjectPaginatorApplier::class)
        ->args([
            tagged_iterator('rekalogika.api_lite.paginator_applier')
        ]);

    $services
        ->set(RekapagerSelectablePaginatorApplier::class)
        ->args([
            '$pagerFactory' => service(PagerFactoryInterface::class),
        ])
        ->tag('rekalogika.api_lite.paginator_applier', [
            'priority' => -50,
        ]);

    $services
        ->set(RekapagerQueryBuilderPaginatorApplier::class)
        ->args([
            '$pagerFactory' => service(PagerFactoryInterface::class),
        ])
        ->tag('rekalogika.api_lite.paginator_applier', [
            'priority' => -50,
        ]);

    $services
        ->set(CollectionPaginatorApplier::class)
        ->args([
            '$pagination' => service(Pagination::class),
        ])
        ->tag('rekalogika.api_lite.paginator_applier', [
            'priority' => -101,
        ]);

    $services
        ->set(SelectablePaginatorApplier::class)
        ->args([
            '$pagination' => service(Pagination::class),
        ])
        ->tag('rekalogika.api_lite.paginator_applier', [
            'priority' => -100,
        ]);

    $services
        ->set(DoctrineOrmPaginatorApplier::class)
        ->args([
            '$pagination' => service(Pagination::class),
        ])
        ->tag('rekalogika.api_lite.paginator_applier', [
            'priority' => -100,
        ]);

    $services->alias(PaginatorApplierInterface::class, 'rekalogika.api_lite.paginator_applier.chain');

    $services
        ->set(ApiMapperInterface::class, ApiMapper::class)
        ->args([
            '$mapper' => service(MapperInterface::class),
            '$objectCacheFactory' => service('rekalogika.mapper.object_cache_factory')
        ])
        ->tag('kernel.reset', ['method' => 'reset']);

    $services
        ->set(ApiCollectionMapperInterface::class, ApiCollectionMapper::class)
        ->args([
            '$mapper' => service(ApiMapperInterface::class),
            '$paginatorApplier' => service(PaginatorApplierInterface::class),
        ]);
};
