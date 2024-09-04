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

use Rekalogika\Mapper\MapperInterface;
use Symfony\Contracts\Service\ResetInterface;

interface ApiMapperInterface extends MapperInterface, ResetInterface {}
