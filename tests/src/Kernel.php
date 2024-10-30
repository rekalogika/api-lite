<?php

declare(strict_types=1);

namespace App;

use ApiPlatform\Symfony\Bundle\ApiPlatformBundle;
use Composer\InstalledVersions;
use Composer\Semver\VersionParser;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Rekalogika\ApiLite\RekalogikaApiLiteBundle;
use Rekalogika\Mapper\RekalogikaMapperBundle;
use Rekalogika\Rekapager\ApiPlatform\RekalogikaRekapagerApiPlatformBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        registerContainerConfiguration as private baseRegisterContainerConfiguration;
    }

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(
        string $environment = 'test',
        bool $debug = true,
        private array $config = []
    ) {
        parent::__construct($environment, $debug);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new DebugBundle();
        yield new DoctrineBundle();
        yield new RekalogikaMapperBundle();
        yield new RekalogikaApiLiteBundle();
        yield new RekalogikaRekapagerApiPlatformBundle();
        yield new ApiPlatformBundle();
        yield new TwigBundle();
        yield new WebProfilerBundle();
        yield new DoctrineFixturesBundle();
        yield new SecurityBundle();
        yield new MakerBundle();
        yield new ZenstruckFoundryBundle();
    }

    public function getConfigDir(): string
    {
        return __DIR__ . '/../config/';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $this->baseRegisterContainerConfiguration($loader);

        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('rekalogika_api_lite', $this->config);
        });

        $loader->load(function (ContainerBuilder $container): void {
            if (InstalledVersions::satisfies(new VersionParser(), 'api-platform/core', '3.*')) {
                $container->loadFromExtension('api_platform', [
                    'event_listeners_backward_compatibility_layer' => false,
                    'keep_legacy_inflector' => false,
                ]);
            } elseif (InstalledVersions::satisfies(new VersionParser(), 'api-platform/core', '4.*')) {
                $container->loadFromExtension('api_platform', [
                    'serializer'  => [
                        'hydra_prefix' => true,
                    ],
                ]);
            }
        });
    }
}
