<?php

namespace App\Tests\Entity;


use PHPUnit\Framework\TestCase;


class fileExistsTest extends TestCase
{
    public function testDefaultImageExists()
    {
        $this->assertFileExists('public/images/default_trickImage.jpg');
    }

    public function testLogoExists()
    {
        $this->assertFileExists('public/images/logo.png');
    }
}