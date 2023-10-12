<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load( ObjectManager $manager ) : void
    {
        $manager->flush();
    }

    public function getOrder() : int
    {
        return 1;
    }
}
