<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use Symfony\Component\Validator\Constraints as Assert;

class ReviewInputDtoWithBookInputDtoProperty
{
    public BookInputDto $book;

    public string $body;

    /**
     * @var int<1,5>
     */
    #[Assert\Range(min: 1, max: 5)]
    public int $rating;
}
