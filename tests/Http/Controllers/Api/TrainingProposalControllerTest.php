<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use Queue;
use Response;
use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;

class TrainingProposalControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = TrainingProposalTest::create(['job_id' => $id]);
        AnnotationCandidateTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/training-proposals");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")
            ->assertStatus(200)
            ->assertJson([[
                'id' => $annotation->id,
                'selected' => $annotation->selected,
                'image_id' => $annotation->image_id,
            ]]);
    }

    public function testUpdate()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::trainingProposalsId(),
        ]);
        $a = TrainingProposalTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('PUT', "/api/v1/maia/training-proposals/{$a->id}");

        $this->beGuest();
        $this->putJson("/api/v1/maia/training-proposals/{$a->id}")->assertStatus(403);

        $this->beEditor();
        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", [])
            // Must not be empty.
            ->assertStatus(422);

        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", [
                // Must be bool.
                'selected' => 123,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", [
                // Must be points of a circle.
                'selected' => true,
                'points' => [10, 20],
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", [
                'selected' => true,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(200);

        $a->refresh();
        $this->assertTrue($a->selected);
        $this->assertEquals([10, 20, 30], $a->points);

    }

    public function testUpdateAfterState()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::instanceSegmentationId(),
        ]);
        $a = TrainingProposalTest::create(['job_id' => $job->id]);
        $this->beEditor();
        // Training proposals cannot be modified after the training proposal state.
        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", [
                'selected' => true,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(422);
    }

    public function testUpdatePoints()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::trainingProposalsId(),
        ]);
        $a = TrainingProposalTest::create(['job_id' => $job->id]);

        Queue::fake();
        $this->beEditor();
        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", ['selected' => true])
            ->assertStatus(200);

        Queue::assertNotPushed(GenerateAnnotationPatch::class);

        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", ['points' => [10, 20, 30]])
            ->assertStatus(200);

        Queue::assertPushed(GenerateAnnotationPatch::class);
    }

    public function testShowFile()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = TrainingProposalTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('GET', "/api/v1/maia/training-proposals/{$a->id}/file");

        $this->beGuest();
        $this->getJson("/api/v1/maia/training-proposals/{$a->id}/file")->assertStatus(403);

        $this->beEditor();
        Response::shouldReceive('download')->once();
        $this->getJson("/api/v1/maia/training-proposals/{$a->id}/file")->assertStatus(200);
    }
}
