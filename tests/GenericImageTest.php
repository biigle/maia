<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\FileCache\GenericFile;
use Biigle\Modules\Maia\GenericImage;
use TestCase;

class GenericImageTest extends TestCase
{
    public function testInstance()
    {
        $i = new GenericImage(1, 'url');
        $this->assertTrue($i instanceof GenericFile);
    }

    public function testAttributes()
    {
        $i = new GenericImage(1, 'url');
        $this->assertEquals(1, $i->getId());
        $this->assertEquals('url', $i->getUrl());
    }
}
