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

use ApiPlatform\State\ProcessorInterface;

/**
 * @template TInput
 * @template TOutput
 * @implements ProcessorInterface<TInput,TOutput>
 */
abstract class AbstractProcessor extends AbstractState implements ProcessorInterface
{
}
