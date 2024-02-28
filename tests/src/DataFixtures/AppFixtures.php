<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        BookFactory::createMany(25, function () {
            return [
                'reviews' => ReviewFactory::createMany(10),
            ];
        });
    }
}
