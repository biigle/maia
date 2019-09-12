<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Tests\ImageTest;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;

class MaiaJobControllerTest extends ApiTestCase
{
    protected $defaultParams;

    public function setUp(): void
    {
        parent::setUp();
        $this->defaultParams = [
            'nd_clusters' => 1,
            'nd_patch_size' => 39,
            'nd_threshold' => 99,
            'nd_latent_size' => 0.1,
            'nd_trainset_size' => 10000,
            'nd_epochs' => 100,
            'nd_stride' => 2,
            'nd_ignore_radius' => 5,
            'is_epochs_head' => 20,
            'is_epochs_all' => 10,
        ];
        ImageTest::create(['volume_id' => $this->volume()->id]);
    }

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
            'nd_clusters' => 5,
            'nd_patch_size' => 40,
            'nd_threshold' => 99,
            'nd_latent_size' => 0.1,
            'nd_trainset_size' => 10000,
            'nd_epochs' => 100,
            'nd_stride' => 2,
            'nd_ignore_radius' => 5,
            'is_epochs_head' => 20,
            'is_epochs_all' => 10,
        ])->assertStatus(422);

        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();

        $job = MaiaJob::first();
        $this->assertNotNull($job);
        $this->assertEquals($id, $job->volume_id);
        $this->assertEquals($this->editor()->id, $job->user_id);
        $this->assertEquals(State::noveltyDetectionId(), $job->state_id);
        $this->assertEquals($this->defaultParams, $job->params);

        // only one running job at a time
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertStatus(422);
    }

    public function testStoreFailedNoveltyDetection()
    {
        $id = $this->volume()->id;
        $job = MaiaJobTest::create([
            'state_id' => State::failedNoveltyDetectionId(),
            'volume_id' => $this->volume()->id,
        ]);

        $this->beEditor();
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();
    }

    public function testStoreFailedInstanceSegmentation()
    {
        $id = $this->volume()->id;
        $job = MaiaJobTest::create([
            'state_id' => State::failedInstanceSegmentationId(),
            'volume_id' => $this->volume()->id,
        ]);

        $this->beEditor();
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();
    }

    public function testStoreTiledImages()
    {
        $id = $this->volume()->id;
        ImageTest::create(['volume_id' => $id, 'tiled' => true, 'filename' => 'x']);

        $this->beEditor();
        // MAIA is not available for volumes with tiled images.
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertStatus(422);
    }

    public function testStoreUseExisting()
    {
        $id = $this->volume()->id;
        $this->beEditor();
        $this->defaultParams['use_existing'] = 'abc';
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            // Parameter must be bool.
            ->assertStatus(422);

        $this->defaultParams['use_existing'] = true;
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();
        $job = MaiaJob::first();
        $this->assertTrue($job->shouldUseExistingAnnotations());
    }

    public function testStoreRestrictLabels()
    {
        $id = $this->volume()->id;
        $this->beEditor();
        $this->defaultParams['restrict_labels'] = [$this->labelChild()->id];
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            // Requires 'use_existing'.
            ->assertStatus(422);

        $this->defaultParams['use_existing'] = true;
        $this->defaultParams['restrict_labels'] = [999];
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            // Must contain valid label IDs.
            ->assertStatus(422);

        $this->defaultParams['restrict_labels'] = [$this->labelChild()->id];
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();
        $job = MaiaJob::first();
        $this->assertArrayHasKey('restrict_labels', $job->params);
        $this->assertEquals([$this->labelChild()->id], $job->params['restrict_labels']);
    }

    public function testStoreSkipNd()
    {
        $id = $this->volume()->id;
        $this->beEditor();
        $params = [
            'skip_nd' => true,
            'nd_clusters' => 10,
            'is_epochs_head' => 1,
            'is_epochs_all' => 1,
        ];
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)
            // Requires 'use_existing'.
            ->assertStatus(422);

        $params['use_existing'] = true;
        $params['skip_nd'] = 'abc';
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)
            // Must be bool.
            ->assertStatus(422);

        $params['skip_nd'] = true;
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $params)
            // nd_* parameters are no longer required.
            ->assertSuccessful();
        $job = MaiaJob::first();
        $this->assertTrue($job->shouldSkipNoveltyDetection());
        $this->assertArrayNotHasKey('nd_clusters', $job->params);
    }

    public function testStoreNdClustersTooFewImages()
    {
        $id = $this->volume()->id;
        $this->beEditor();
        $this->defaultParams['nd_clusters'] = 2;
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertStatus(422);

        $this->defaultParams['skip_nd'] = true;
        $this->defaultParams['use_existing'] = true;
        $this->postJson("/api/v1/volumes/{$id}/maia-jobs", $this->defaultParams)
            ->assertSuccessful();
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
