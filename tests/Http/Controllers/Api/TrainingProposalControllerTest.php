<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Event;
use Queue;
use Response;

class TrainingProposalControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $label = LabelTest::create();

        $annotation = TrainingProposalTest::create(['job_id' => $id, 'label_id' => $label->id]);
        AnnotationCandidateTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/training-proposals");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")
            ->assertStatus(200)
            ->assertExactJson([[
                'id' => $annotation->id,
                'label' => $annotation->$label,
                'selected' => $annotation->selected,
                'image_id' => $annotation->image_id,
                'uuid' => $annotation->image->uuid,
            ]]);
    }

    public function testSubmit()
    {
        $job = MaiaJobTest::create([
            'state_id' => State::noveltyDetectionId(),
            'volume_id' => $this->volume()->id,
        ]);
        $this->doTestApiRoute('POST', "/api/v1/maia-jobs/{$job->id}/training-proposals");

        $this->beGuest();
        $this->postJson("/api/v1/maia-jobs/{$job->id}/training-proposals")->assertStatus(403);

        $this->beEditor();
        // The job can only continue from training proposals state.
        $this->postJson("/api/v1/maia-jobs/{$job->id}/training-proposals")->assertStatus(422);

        $job->state_id = State::trainingProposalsId();
        $job->save();

        // The job cannot continue if it has no selected training proposals.
        $this->postJson("/api/v1/maia-jobs/{$job->id}/training-proposals")->assertStatus(422);

        TrainingProposalTest::create([
            'job_id' => $job->id,
            'selected' => true,
        ]);

        Event::fake();
        $this->postJson("/api/v1/maia-jobs/{$job->id}/training-proposals")->assertStatus(200);
        Event::assertDispatched(MaiaJobContinued::class);
        $this->assertEquals(State::instanceSegmentationId(), $job->fresh()->state_id);

        // Job is no longer in training proposal state.
        $this->postJson("/api/v1/maia-jobs/{$job->id}/training-proposals")->assertStatus(422);
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

        Queue::assertNotPushed(GenerateImageAnnotationPatch::class);

        $this->putJson("/api/v1/maia/training-proposals/{$a->id}", ['points' => [10, 20, 30]])
            ->assertStatus(200);

        Queue::assertPushed(GenerateImageAnnotationPatch::class);
    }
}
