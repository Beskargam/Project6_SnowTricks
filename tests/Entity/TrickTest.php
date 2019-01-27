<?php

namespace App\Tests\Entity;


use App\Entity\GroupTrick;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class TrickTest extends TestCase
{
    public function testAddTrick()
    {
        $groupTrick = new GroupTrick();
        $groupTrick->setName('Grab');
        $trick = new Trick();
        $trick->setTitle('test Title');
        $trick->setContent('test description');
        $trick->setGroupTrick($groupTrick);

        $this->assertNotNull($trick->getGroupTrick());
    }
}