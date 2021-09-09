<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\ImageTest;

class AreaKnowledgeTransferVolumeControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $volume = $this->volume();
        $this->doTestApiRoute('GET', "/api/v1/volumes/filter/area-knowledge-transfer");

        $this->beGuest();
        $this->getJson("/api/v1/volumes/filter/area-knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([]);

        $image = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
            'volume_id' => $volume->id,
        ]);

        $this->getJson("/api/v1/volumes/filter/area-knowledge-transfer")
            ->assertStatus(200)
            // No annotations
            ->assertExactJson([]);

        ImageAnnotationTest::create(['image_id' => $image->id]);

        $this->getJson("/api/v1/volumes/filter/area-knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    'id' => $volume->id,
                    'name' => $volume->name,
                    'projects' => [
                        [
                            'id' => $volume->projects[0]->id,
                            'name' => $volume->projects[0]->name,
                        ],
                    ],
                ],
            ]);

        $this->beUser();
        $this->getJson("/api/v1/volumes/filter/area-knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function testIndexLaserpoints()
    {
        $volume = $this->volume();

        $this->beGuest();
        $image = ImageTest::create([
            'attrs' => ['laserpoints' => ['area' => 1]],
            'volume_id' => $volume->id,
        ]);

        ImageAnnotationTest::create(['image_id' => $image->id]);

        $this->getJson("/api/v1/volumes/filter/area-knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([
                [
                    'id' => $volume->id,
                    'name' => $volume->name,
                    'projects' => [
                        [
                            'id' => $volume->projects[0]->id,
                            'name' => $volume->projects[0]->name,
                        ],
                    ],
                ],
            ]);
    }
}
