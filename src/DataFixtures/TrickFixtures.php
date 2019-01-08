<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $trick = new Trick();
        $trick->setTitle('Mute');
        $trick->setContent(
            'Le Mute consiste en une saisie de la carre frontside de la planche entre les deux 
            pieds avec la main avant');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_GRAB_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_MUTE1_REFERENCE);
        $trick->addImage($image);
        $image = $this->getReference(ImageFixtures::IMAGE_MUTE2_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Sad');
        $trick->setContent(
            'Aussi appelé Melancholie ou Stale Week, le Sad consiste en une saisie de la 
            carre backside de la planche, entre les deux pieds, avec la main avant');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_GRAB_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_SAD1_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Indy');
        $trick->setContent(
            'Le Indy consiste en une saisie de la carre frontside de la planche, entre 
            les deux pieds, avec la main arrière');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_GRAB_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_INDY1_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Stalefish');
        $trick->setContent(
            'Le Stalefush consiste en une saisie de la carre backside de la planche 
            entre les deux pieds avec la main arrière');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_GRAB_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_STALEFISH1_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('180');
        $trick->setContent(
            'Le 180 désigne un demi-tour, soit 180 degrés d\'angle');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_ROTATION_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_180_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('360');
        $trick->setContent(
            'Le trois six désigne un tour complet');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_ROTATION_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_360_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('540');
        $trick->setContent(
            'Le cinq quatre désigne un tour et demi');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_ROTATION_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $video = new Video();
        $video->setUrl('https://www.youtube.com/embed/w66AU3GdfFo');
        $video->setTrick($trick);
        $trick->addVideo($video);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Front Flip');
        $trick->setContent(
            'Un Flip est une rotation verticale. Le Front Flip se distingue par sa rotation en avant');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_FLIP_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_FRONTFLIP_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Back Flip');
        $trick->setContent(
            'Un Flip est une rotation verticale. Le Back Flip se distingue par sa rotation en arrière');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_FLIP_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $image = $this->getReference(ImageFixtures::IMAGE_BACKFLIP_REFERENCE);
        $trick->addImage($image);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('Nose Slide');
        $trick->setContent(
            'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit 
            avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins 
            désaxé. Le Nose Slide est un Slide sur l\'avant de la planche sur la barre.');
        $groupTrick = $this->getReference((GroupTrickFixtures::GROUP_TRICK_SLIDE_REFERENCE));
        $trick->setGroupTrick($groupTrick);
        $video = new Video();
        $video->setUrl('https://www.dailymotion.com/embed/video/xxxu60');
        $video->setTrick($trick);
        $trick->addVideo($video);
        $manager->persist($trick);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            GroupTrickFixtures::class,
            ImageFixtures::class,
        );
    }
}
