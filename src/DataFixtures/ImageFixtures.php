<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public const IMAGE_MUTE1_REFERENCE = 'mute1';
    public const IMAGE_MUTE2_REFERENCE = 'mute2';
    public const IMAGE_SAD1_REFERENCE = 'sad1';
    public const IMAGE_INDY1_REFERENCE = 'indy1';
    public const IMAGE_STALEFISH1_REFERENCE = 'stalefish1';
    public const IMAGE_180_REFERENCE = '180';
    public const IMAGE_360_REFERENCE = '360';
    public const IMAGE_FRONTFLIP_REFERENCE = 'frontflip';
    public const IMAGE_BACKFLIP_REFERENCE = 'backflip';

    public function load(ObjectManager $manager)
    {
        $image = new Image();
        $image->setName('mute1.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_MUTE1_REFERENCE, $image);

        $image = new Image();
        $image->setName('mute2.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_MUTE2_REFERENCE, $image);

        $image = new Image();
        $image->setName('sad1.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_SAD1_REFERENCE, $image);

        $image = new Image();
        $image->setName('indy1.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_INDY1_REFERENCE, $image);

        $image = new Image();
        $image->setName('stalefish1.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_STALEFISH1_REFERENCE, $image);

        $image = new Image();
        $image->setName('180.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_180_REFERENCE, $image);

        $image = new Image();
        $image->setName('360.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_360_REFERENCE, $image);

        $image = new Image();
        $image->setName('frontflip.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_FRONTFLIP_REFERENCE, $image);

        $image = new Image();
        $image->setName('backflip.jpg');
        $manager->persist($image);
        $this->addReference(self::IMAGE_BACKFLIP_REFERENCE, $image);


        $manager->flush();

    }
}
