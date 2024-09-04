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

use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    $services
        ->instanceof(
            PaginatorApplierInterface::class,
        )
        ->tag('rekalogika.api_lite.paginator_applier');

    $services
        ->load('App\\', '../src/')
        ->exclude('../src/{Entity,ApiInput,Exception}');
};
