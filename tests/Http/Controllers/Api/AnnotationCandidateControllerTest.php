<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use Queue;
use Response;
use ApiTestCase;
use Biigle\Tests\LabelTest;
use Biigle\Tests\AnnotationTest;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;

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
            ->assertJson([[
                'id' => $annotation->id,
                'image_id' => $annotation->image_id,
                'label' => null,
            ]]);
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
        $annotation = AnnotationTest::create();
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

        Queue::assertPushed(GenerateAnnotationPatch::class);
    }

    public function testShowFile()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = AnnotationCandidateTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('GET', "/api/v1/maia/annotation-candidates/{$a->id}/file");

        $this->beGuest();
        $this->getJson("/api/v1/maia/annotation-candidates/{$a->id}/file")->assertStatus(403);

        $this->beEditor();
        Response::shouldReceive('download')->once();
        $this->getJson("/api/v1/maia/annotation-candidates/{$a->id}/file")->assertStatus(200);
    }
}
