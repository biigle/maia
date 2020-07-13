<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;

class MaiaJobImagesControllerTest extends ApiTestCase
{
    public function testIndexTrainingProposals()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = TrainingProposalTest::create([
            'job_id' => $id,
            'points' => [10, 20, 30],
        ]);

        $imageId = $annotation->image_id;

        TrainingProposalTest::create(['job_id' => $id]);
        AnnotationCandidateTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/images/{$imageId}/training-proposals");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/images/{$imageId}/training-proposals")
            ->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/images/{$imageId}/training-proposals")
            ->assertStatus(200)
            ->assertJson([$annotation->id => [10, 20, 30]]);
    }

    public function testIndexAnnotationCandidates()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = AnnotationCandidateTest::create([
            'job_id' => $id,
            'points' => [10, 20, 30],
        ]);

        $imageId = $annotation->image_id;

        AnnotationCandidateTest::create(['job_id' => $id]);
        TrainingProposalTest::create(['job_id' => $id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/images/{$imageId}/annotation-candidates");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/images/{$imageId}/annotation-candidates")
            ->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/images/{$imageId}/annotation-candidates")
            ->assertStatus(200)
            ->assertJson([$annotation->id => [10, 20, 30]]);
    }
}
