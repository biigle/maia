<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Illuminate\Testing\TestResponse;
use Queue;
use Symfony\Component\HttpFoundation\Response;

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
        $response = $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")
            ->assertStatus(200);

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $response = new TestResponse(
            new Response($content,
                $response->baseResponse->getStatusCode(),
                $response->baseResponse->headers->all()
            )
        );

        $response->assertExactJson([[
                'id' => $annotation->id,
                'image_id' => $annotation->image_id,
                'label' => null,
                'annotation_id' => null,
                'uuid' => $annotation->image->uuid,
            ]]);
    }

    public function testSubmit()
    {
        $job = MaiaJobTest::create([
            'state_id' => State::objectDetectionId(),
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
        $this->assertSame([10, 20, 30], $a->points);
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

        $this->assertSame($this->labelRoot()->id, $a->fresh()->label_id);

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

        Queue::assertPushed(ProcessObjectDetectedImage::class, function ($job) use ($a) {
            $this->assertSame([$a->id], $job->only);
            $this->assertFalse($job->skipFeatureVectors);

            return true;
        });
    }

    public function testIndexSimilarity()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $ac1 = AnnotationCandidateTest::create(['job_id' => $id]);
        $ac2 = AnnotationCandidateTest::create(['job_id' => $id]);
        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac2->id,
            'job_id' => $id,
            'vector' => range(10, 393),
        ]);
        $ac3 = AnnotationCandidateTest::create(['job_id' => $id]);
        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac3->id,
            'job_id' => $id,
            'vector' => range(1, 384),
        ]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}")
            ->assertStatus(404);

        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);

        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}")
            ->assertStatus(200)
            ->assertExactJson([$ac3->id, $ac2->id]);
    }

    public function testIndexSimilarityMissing()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $ac1 = AnnotationCandidateTest::create(['job_id' => $id]);
        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);
        $ac2 = AnnotationCandidateTest::create(['job_id' => $id]);
        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac2->id,
            'job_id' => $id,
            'vector' => range(10, 393),
        ]);
        $ac3 = AnnotationCandidateTest::create(['job_id' => $id]);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}")
            ->assertStatus(200)
            ->assertExactJson([$ac2->id, $ac3->id]);
    }

    public function testIndexSimilarityEmpty()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $ac1 = AnnotationCandidateTest::create(['job_id' => $id]);
        AnnotationCandidateFeatureVector::factory()->create([
            'id' => $ac1->id,
            'job_id' => $id,
            'vector' => range(0, 383),
        ]);
        $ac2 = AnnotationCandidateTest::create(['job_id' => $id]);
        $ac3 = AnnotationCandidateTest::create(['job_id' => $id]);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates/similar-to/{$ac1->id}")
            ->assertStatus(404);
    }
}
