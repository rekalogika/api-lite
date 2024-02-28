<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use ApiPlatform\Metadata\ApiProperty;
use App\ApiResource\User\BookDto;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewInputDtoWithBookDtoPropertyWritableLinkTrue
{
    #[ApiProperty(writableLink: true)]
    public BookDto $book;

    public string $body;

    /**
     * @var int<1,5>
     */
    #[Assert\Range(min: 1, max: 5)]
    public int $rating;
}
