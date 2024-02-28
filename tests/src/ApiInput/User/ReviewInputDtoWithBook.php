<?php

declare(strict_types=1);

namespace App\ApiInput\User;

use ApiPlatform\Metadata\ApiProperty;
use App\ApiResource\User\BookDto;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewInputDtoWithBook
{
    #[ApiProperty(writableLink: false)]
    public BookDto $book;

    public string $body;

    /**
     * @var int<1,5>
     */
    #[Assert\Range(min: 1, max: 5)]
    #[ApiProperty(example: 5)]
    public int $rating;
}
