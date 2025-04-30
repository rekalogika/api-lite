<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array<string,mixed> $criteria, ?array<string,string> $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array<string,mixed> $criteria, ?array<string,string> $orderBy = null, $limit = null, $offset = null)
 */
final class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // @phpstan-ignore method.internalClass
        parent::__construct($registry, Review::class);
    }
}
