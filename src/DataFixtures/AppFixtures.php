<?php

namespace App\DataFixtures;

use App\Factory\ProjectFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->create([
            'email' => 'foo@bar.com',
            'password' => 'password',
        ]);

        UserFactory::new()->createMany(9);

        ProjectFactory::new()->createMany(30);
    }
}
