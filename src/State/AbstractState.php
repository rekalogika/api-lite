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

namespace Rekalogika\ApiLite\State;

use ApiPlatform\Metadata\Operation;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Rekalogika\ApiLite\Exception\LogicException;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\Mapper\ApiCollectionMapperInterface;
use Rekalogika\ApiLite\Mapper\ApiMapperInterface;
use Rekalogika\Mapper\Context\Context;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractState implements ServiceSubscriberInterface
{
    protected ?ContainerInterface $container = null;

    #[Required]
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public static function getSubscribedServices(): array
    {
        return [
            ApiMapperInterface::class,
            ApiCollectionMapperInterface::class,
            AuthorizationCheckerInterface::class => '?' . AuthorizationCheckerInterface::class,
            TokenStorageInterface::class => '?' . TokenStorageInterface::class,
        ];
    }

    /**
     * @template T of object
     * @param class-string<T> $service
     * @return T
     */
    private function get(string $service): object
    {
        $result = $this->container?->get($service);
        assert($result instanceof $service);

        return $result;
    }

    protected function resetMapper(): void
    {
        $this->get(ApiMapperInterface::class)->reset();
    }

    /**
     * @template T of object
     * @param class-string<T>|T $target
     * @return T
     */
    protected function map(
        object $source,
        string|object $target,
        ?Context $context = null
    ): object {
        return $this->get(ApiMapperInterface::class)
            ->map($source, $target, $context);
    }

    /**
     * @template TOutput of object
     * @param class-string<TOutput> $target
     * @param array<string,mixed> $context
     * @return iterable<TOutput>
     */
    protected function mapCollection(
        object $collection,
        string $target,
        Operation $operation,
        array $context = [],
        ?Context $mapperContext = null,
    ): iterable {
        return $this
            ->get(ApiCollectionMapperInterface::class)
            ->mapCollection(
                $collection,
                $target,
                $operation,
                $context,
                $mapperContext
            );
    }

    /**
     * Get a user from the Security Token Storage.
     */
    protected function getUser(): ?UserInterface
    {
        try {
            return $this->get(TokenStorageInterface::class)->getToken()?->getUser();
        } catch (NotFoundExceptionInterface) {
            throw new LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }
    }

    /**
     * Checks if the attribute is granted against the current authentication
     * token and optionally supplied subject.
     */
    protected function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        try {
            return $this->get(AuthorizationCheckerInterface::class)->isGranted($attribute, $subject);
        } catch (NotFoundExceptionInterface) {
            throw new LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }
    }

    /**
     * Throws an exception unless the attribute is granted against the current authentication token and optionally
     * supplied subject.
     *
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attribute, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes([$attribute]);
            $exception->setSubject($subject);

            throw $exception;
        }
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     */
    protected function createNotFoundException(string $message = 'Not Found', \Throwable $previous = null): NotFoundException
    {
        return new NotFoundException($message, $previous);
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @throws \LogicException If the Security component is not available
     */
    protected function createAccessDeniedException(string $message = 'Access Denied.', \Throwable $previous = null): AccessDeniedException
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('You cannot use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }
}
