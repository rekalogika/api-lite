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

namespace Rekalogika\ApiLite\Exception;

use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(404)]
class NotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Not Found', \Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
