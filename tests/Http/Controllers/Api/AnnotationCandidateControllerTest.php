<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Queue;
use Response;

class AnnotationCandidateControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = AnnotationCandidateTest::create(['job_id' => $id]);
        TrainingProposalTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/annotation-candidates");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")
            ->assertStatus(200)
            ->assertExactJson([[
                'id' => $annotation->id,
                'image_id' => $annotation->image_id,
                'label' => null,
                'label_id' => null,
                'annotation_id' => null,
                'uuid' => $annotation->image->uuid,
            ]]);
    }

    public function testIndexWithLabelId()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $label = LabelTest::create(["label_source_id"=>null, "source_id" => null]);
        $annotation = AnnotationCandidateTest::create(['job_id' => $id, 'label_id' => $label->id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/annotation-candidates");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")->assertStatus(403);

        $this->beEditor();
        $label = $label->toArray();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")
            ->assertStatus(200)
            ->assertExactJson([[
                'id' => $annotation->id,
                'image_id' => $annotation->image_id,
                'label_id' => $annotation->label_id,
                'label' => $label,
                'annotation_id' => null,
                'uuid' => $annotation->image->uuid,
            ]]);
    }

    public function testSubmit()
    {
        $job = MaiaJobTest::create([
            'state_id' => State::instanceSegmentationId(),
            'volume_id' => $this->volume()->id,
            'attrs' => ['converting_candidates' => true],
        ]);
        $this->doTestApiRoute('POST', "/api/v1/maia-jobs/{$job->id}/annotation-candidates");

        $this->beGuest();
        $this->postJson("/api/v1/maia-jobs/{$job->id}/annotation-candidates")
            ->assertStatus(403);

        $this->beEditor();
        // Annotation candidates can only be submitted from annotation candidate state.
        $this->postJson("/api/v1/maia-jobs/{$job->id}/annotation-candidates")
            ->assertStatus(422);

        $job->state_id = State::annotationCandidatesId();
        $job->save();

        // The "job in progress" flag is still set
        $this->postJson("/api/v1/maia-jobs/{$job->id}/annotation-candidates")
            ->assertStatus(422);

        $job->convertingCandidates = false;
        $job->save();

        Queue::fake();

        $this->postJson("/api/v1/maia-jobs/{$job->id}/annotation-candidates")
            ->assertStatus(200);

        Queue::assertPushed(ConvertAnnotationCandidates::class, function ($j) use ($job) {
            return $j->job->id === $job->id;
        });

        $this->assertTrue($job->fresh()->convertingCandidates);
    }

    public function testUpdate()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = AnnotationCandidateTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('PUT', "/api/v1/maia/annotation-candidates/{$a->id}");

        $this->beGuest();
        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}")->assertStatus(403);

        $this->beEditor();
        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                // Must be points of a circle.
                'points' => [10, 20],
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'points' => [10, 20, 30],
            ])
            ->assertStatus(200);

        $a->refresh();
        $this->assertEquals([10, 20, 30], $a->points);
    }

    public function testUpdateLabel()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = AnnotationCandidateTest::create(['job_id' => $job->id]);

        $label = LabelTest::create();

        $this->beEditor();
        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'label_id' => $label->id,
            ])
            // Label does not belong to the volume.
            ->assertStatus(403);

        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'label_id' => 9999,
            ])
            // Label does not exist.
            ->assertStatus(422);

        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'label_id' => $this->labelRoot()->id,
            ])
            ->assertStatus(200);

        $this->assertEquals($this->labelRoot()->id, $a->fresh()->label_id);

        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'label_id' => null,
            ])
            ->assertStatus(200);

        $this->assertNull($a->fresh()->label_id);
    }

    public function testUpdateConverted()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $annotation = ImageAnnotationTest::create();
        $a = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'annotation_id' => $annotation->id,
        ]);

        $this->beEditor();
        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", [
                'points' => [10, 20, 30],
            ])
            // Cannot be updated because it is already converted.
            ->assertStatus(422);
    }

    public function testUpdatePoints()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = AnnotationCandidateTest::create(['job_id' => $job->id]);

        Queue::fake();
        $this->beEditor();
        $this->putJson("/api/v1/maia/annotation-candidates/{$a->id}", ['points' => [10, 20, 30]])
            ->assertStatus(200);

        Queue::assertPushed(GenerateImageAnnotationPatch::class);
    }
}
