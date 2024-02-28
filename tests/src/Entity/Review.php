<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true, nullable: false)]
    private Uuid $id;

    /**
     * @var int<1,5>
     */
    #[ORM\Column]
    private int $rating = 3;

    #[ORM\Column]
    private ?string $body = null;

    #[ORM\ManyToOne(
        targetEntity: Book::class,
        inversedBy: 'reviews',
    )]
    private ?Book $book = null;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }


    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return int<1,5>
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int<1,5> $rating
     */
    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
