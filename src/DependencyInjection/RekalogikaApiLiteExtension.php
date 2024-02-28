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

use App\Kernel;
use Rekalogika\ApiLite\PaginatorApplier\PaginatorApplierInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @internal
 */
final class RekalogikaApiLiteExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loaderTest = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../tests/config'));

        // load services

        $loader->import('services.php');

        // load service configuration for test environment

        $env = $container->getParameter('kernel.environment');

        if ($env === 'test' && class_exists(Kernel::class)) {
            $loaderTest->import('services.php');
        }

        // autoconfiguration

        $container->registerForAutoconfiguration(PaginatorApplierInterface::class)
            ->addTag('rekalogika.api_lite.paginator_applier');
    }
}
