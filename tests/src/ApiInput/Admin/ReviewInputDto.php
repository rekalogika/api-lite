<?php

declare(strict_types=1);

namespace App\ApiInput\Admin;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewInputDto
{
    public string $body;

    /**
     * @var int<1,5>
     */
    #[Assert\Range(min: 1, max: 5)]
    #[ApiProperty(example: 5, description: 'Rating of the review')]
    public int $rating;
}
