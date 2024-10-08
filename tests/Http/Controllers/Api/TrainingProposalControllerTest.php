<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\ProcessNoveltyDetectedImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
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

        $annotation = TrainingProposalTest::create(['job_id' => $id]);
        AnnotationCandidateTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/training-proposals");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals")
            ->assertStatus(200)
            ->assertExactJson([[
                'id' => $annotation->id,
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
        $this->assertSame(State::objectDetectionId(), $job->fresh()->state_id);

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
        $this->assertSame([10, 20, 30], $a->points);
    }

    public function testUpdateAfterState()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::objectDetectionId(),
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

        Queue::assertPushed(ProcessNoveltyDetectedImage::class, function ($job) use ($a) {
            $this->assertSame([$a->id], $job->only);
            $this->assertFalse($job->skipFeatureVectors);

            return true;
        });
    }

    public function testIndexSimilarity()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $tp1 = TrainingProposalTest::create(['job_id' => $id]);
        $tp2 = TrainingProposalTest::create(['job_id' => $id]);
        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp2->id,
            'job_id' => $id,
            'vector' => range(10, 393),
        ]);
        $tp3 = TrainingProposalTest::create(['job_id' => $id]);
        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp3->id,
            'job_id' => $id,
            'vector' => range(1, 384),
        ]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}")
            ->assertStatus(404);

        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);

        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}")
            ->assertStatus(200)
            ->assertExactJson([$tp3->id, $tp2->id]);
    }

    public function testIndexSimilarityMissing()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $tp1 = TrainingProposalTest::create(['job_id' => $id]);
        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);
        $tp2 = TrainingProposalTest::create(['job_id' => $id]);
        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp2->id,
            'job_id' => $id,
            'vector' => range(10, 393),
        ]);
        $tp3 = TrainingProposalTest::create(['job_id' => $id]);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}")
            ->assertStatus(200)
            ->assertExactJson([$tp2->id, $tp3->id]);
    }

    public function testIndexSimilarityEmpty()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $tp1 = TrainingProposalTest::create(['job_id' => $id]);
        TrainingProposalFeatureVector::factory()->create([
            'id' => $tp1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);
        $tp2 = TrainingProposalTest::create(['job_id' => $id]);
        $tp3 = TrainingProposalTest::create(['job_id' => $id]);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/training-proposals/similar-to/{$tp1->id}")
            ->assertStatus(404);
    }
}
