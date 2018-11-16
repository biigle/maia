<?php

namespace Biigle\Tests\Modules\Maia;

use TestCase;
use Biigle\Modules\Maia\GenericImage;
use Biigle\ImageCache\GenericImage as ImageCacheImage;

class GenericImageTest extends TestCase
{
    public function testInstance()
    {
        $i = new GenericImage(1, 'url');
        $this->assertTrue($i instanceof ImageCacheImage);
    }

    public function testAttributes()
    {
        $i = new GenericImage(1, 'url');
        $this->assertEquals(1, $i->getId());
        $this->assertEquals('url', $i->getUrl());
    }
}
