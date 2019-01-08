<?php

namespace App\DataFixtures;

use App\Entity\GroupTrick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GroupTrickFixtures extends Fixture
{
    public const GROUP_TRICK_GRAB_REFERENCE = 'group-trick-grab';
    public const GROUP_TRICK_ROTATION_REFERENCE = 'group-trick-rotation';
    public const GROUP_TRICK_FLIP_REFERENCE = 'group-trick-flip';
    public const GROUP_TRICK_SLIDE_REFERENCE = 'group-trick-slide';
    public const GROUP_TRICK_OLD_SCHOOL_REFERENCE = 'group-trick-old-school';

    public function load(ObjectManager $manager)
    {
        $groupTrick = new GroupTrick();
        $groupTrick->setName('Grab');
        $manager->persist($groupTrick);
        $this->addReference(self::GROUP_TRICK_GRAB_REFERENCE, $groupTrick);

        $groupTrick = new GroupTrick();
        $groupTrick->setName('Rotation');
        $manager->persist($groupTrick);
        $this->addReference(self::GROUP_TRICK_ROTATION_REFERENCE, $groupTrick);

        $groupTrick = new GroupTrick();
        $groupTrick->setName('Flip');
        $manager->persist($groupTrick);
        $this->addReference(self::GROUP_TRICK_FLIP_REFERENCE, $groupTrick);

        $groupTrick = new GroupTrick();
        $groupTrick->setName('Slide');
        $manager->persist($groupTrick);
        $this->addReference(self::GROUP_TRICK_SLIDE_REFERENCE, $groupTrick);

        $groupTrick = new GroupTrick();
        $groupTrick->setName('Old School');
        $manager->persist($groupTrick);
        $this->addReference(self::GROUP_TRICK_OLD_SCHOOL_REFERENCE, $groupTrick);

        $manager->flush();
    }
}
