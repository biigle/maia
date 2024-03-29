<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Views;

use ApiTestCase;
use Biigle\MediaType;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;

class MaiaJobControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $id = $this->volume()->id;

        // Not logged in.
        $this->get("volumes/{$id}/maia")->assertStatus(302);

        // Doesn't belong to project.
        $this->beUser();
        $this->get("volumes/{$id}/maia")->assertStatus(403);

        // Not allowed to perform MAIA jobs.
        $this->beGuest();
        $this->get("volumes/{$id}/maia")->assertStatus(403);

        $this->beEditor();
        $this->get("volumes/{$id}/maia")->assertStatus(200);

        $this->beGlobalAdmin();
        $this->get("volumes/{$id}/maia")->assertStatus(200);
    }

    public function testIndexTiledImage()
    {
        $id = $this->volume()->id;
        ImageTest::create(['volume_id' => $id, 'tiled' => true]);
        $this->beEditor();
        $this->get("volumes/{$id}/maia")->assertStatus(404);
    }

    public function testIndexVideoVolume()
    {
        $id = $this->volume(['media_type_id' => MediaType::videoId()])->id;
        $this->beEditor();
        $this->get("volumes/{$id}/maia")->assertStatus(404);
    }

    public function testShow()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);

        // Not logged in.
        $this->get("maia/{$job->id}")->assertStatus(302);

        // Doesn't belong to project.
        $this->beUser();
        $this->get("maia/{$job->id}")->assertStatus(403);

        // Not allowed to see MAIA jobs.
        $this->beGuest();
        $this->get("maia/{$job->id}")->assertStatus(403);

        $this->beEditor();
        $this->get("maia/{$job->id}")->assertStatus(200);

        $this->beGlobalAdmin();
        $this->get("maia/{$job->id}")->assertStatus(200);
    }

    public function testShowAnnotationCandidates()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::annotationCandidatesId(),
        ]);

        $this->beEditor();
        $this->get("maia/{$job->id}")->assertStatus(200);
    }
}
