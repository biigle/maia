<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;

class MaiaJobImagesControllerTest extends ApiTestCase
{
    public function testIndexTrainingProposals()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::trainingProposalId(),
            'points' => [10, 20, 30],
        ]);

        $imageId = $annotation->image_id;

        MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::trainingProposalId(),
        ]);

        MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::annotationCandidateId(),
        ]);

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

        $annotation = MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::annotationCandidateId(),
            'points' => [10, 20, 30],
        ]);

        $imageId = $annotation->image_id;

        MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::annotationCandidateId(),
        ]);

        MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::trainingProposalId(),
        ]);

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
