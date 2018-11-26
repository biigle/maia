<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use Event;
use ApiTestCase;
use Biigle\Tests\ImageTest;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;

class MaiaJobControllerTest extends ApiTestCase
{
    public function testStore()
    {
        $id = $this->volume()->id;
        $this->doTestApiRoute('POST', "/api/v1/volumes/{$id}/maia-jobs");

        $this->beGuest();
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs")->assertStatus(403);

        $this->beEditor();
        // mssing arguments
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs")->assertStatus(422);

        // patch size must be an odd number
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", [
            'clusters' => 5,
            'patch_size' => 40,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ])->assertStatus(422);

        $params = [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ];

        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)->assertStatus(200);

        $job = MaiaJob::first();
        $this->assertNotNull($job);
        $this->assertEquals($id, $job->volume_id);
        $this->assertEquals($this->editor()->id, $job->user_id);
        $this->assertEquals(State::noveltyDetectionId(), $job->state_id);
        $this->assertEquals($params, $job->params);

        // only one running job at a time
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)->assertStatus(422);
    }

    public function testStoreFailedNoveltyDetection()
    {
        $id = $this->volume()->id;
        $job = MaiaJobTest::create([
            'state_id' => State::failedNoveltyDetectionId(),
            'volume_id' => $this->volume()->id,
        ]);

        $this->beEditor();
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ])->assertStatus(200);
    }

    public function testStoreFailedInstanceSegmentation()
    {
        $id = $this->volume()->id;
        $job = MaiaJobTest::create([
            'state_id' => State::failedInstanceSegmentationId(),
            'volume_id' => $this->volume()->id,
        ]);

        $this->beEditor();
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ])->assertStatus(200);
    }

    public function testStoreTiledImages()
    {
        $id = $this->volume()->id;
        ImageTest::create(['volume_id' => $id, 'tiled' => true]);

        $this->beEditor();
        $params = [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ];

        // MAIA is not available for volumes with tiled images.
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)->assertStatus(422);
    }

    public function testUpdate()
    {
        $job = MaiaJobTest::create([
            'state_id' => State::noveltyDetectionId(),
            'volume_id' => $this->volume()->id,
        ]);
        $this->doTestApiRoute('PUT', "/api/v1/maia-jobs/{$job->id}");

        $this->beGuest();
        $this->putJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(403);

        $this->beEditor();
        // The job can only continue from training proposals state.
        $this->putJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(422);

        $job->state_id = State::trainingProposalsId();
        $job->save();

        // The job cannot continue if it has no selected training proposals.
        $this->putJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(422);

        MaiaAnnotationTest::create([
            'job_id' => $job->id,
            'selected' => true,
            'type_id' => Type::trainingProposalId(),
        ]);

        Event::fake();
        $this->putJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(200);
        Event::assertDispatched(MaiaJobContinued::class);
        $this->assertEquals(State::instanceSegmentationId(), $job->fresh()->state_id);

        // Job is no longer in training proposal state.
        $this->putJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(422);
    }

    public function testDestroy()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $this->doTestApiRoute('DELETE', "/api/v1/maia-jobs/{$job->id}");

        $this->beGuest();
        $this->deleteJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(403);

        $this->beEditor();
        // cannot be deleted during novelty detection
        $this->deleteJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(422);

        $job->state_id = State::trainingProposalsId();
        $job->save();

        $this->deleteJson("/api/v1/maia-jobs/{$job->id}")->assertStatus(200);
        $this->assertNull($job->fresh());
    }
}
