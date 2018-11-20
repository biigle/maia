<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;

class AnnotationCandidateControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $id = $job->id;

        $annotation = MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::annotationCandidateId(),
        ]);

        MaiaAnnotationTest::create([
            'job_id' => $id,
            'type_id' => Type::trainingProposalId(),
        ]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$id}/annotation-candidates");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$id}/annotation-candidates")
            ->assertStatus(200)
            ->assertJson([[
                'id' => $annotation->id,
                'points' => $annotation->points,
                'score' => $annotation->score,
                'selected' => $annotation->selected,
                'image_id' => $annotation->image_id,
            ]]);
    }
}
