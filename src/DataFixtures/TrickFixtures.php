<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setTitle('Trick Title '.$i);
            $trick->setContent('Description du trick '.$i);
            $trick->getGroupTrick()->setName('Grab');
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
