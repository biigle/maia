<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\ImageTest;

class KnowledgeTransferVolumeControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $volume = $this->volume();
        $this->doTestApiRoute('GET', "/api/v1/volumes/filter/knowledge-transfer");

        $this->beGuest();
        $this->getJson("/api/v1/volumes/filter/knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([]);

        $image = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
            'volume_id' => $volume->id,
        ]);

        $this->getJson("/api/v1/volumes/filter/knowledge-transfer")
            ->assertStatus(200)
            // No annotations
            ->assertExactJson([]);

        ImageAnnotationTest::create(['image_id' => $image->id]);

        $this->getJson("/api/v1/volumes/filter/knowledge-transfer")
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
        $this->getJson("/api/v1/volumes/filter/knowledge-transfer")
            ->assertStatus(200)
            ->assertExactJson([]);
    }
}
