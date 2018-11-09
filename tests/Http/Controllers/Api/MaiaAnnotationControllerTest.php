<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;

class MaiaAnnotationControllerTest extends ApiTestCase
{
    public function testShowFile()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = MaiaAnnotationTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('GET', "/api/v1/maia-annotations/{$a->id}/file");

        $this->beGuest();
        $this->getJson("/api/v1/maia-annotations/{$a->id}/file")->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-annotations/{$a->id}/file")->assertStatus(200);
    }

    public function testUpdate()
    {
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $a = MaiaAnnotationTest::create(['job_id' => $job->id]);

        $this->doTestApiRoute('PUT', "/api/v1/maia-annotations/{$a->id}");

        $this->beGuest();
        $this->putJson("/api/v1/maia-annotations/{$a->id}")->assertStatus(403);

        $this->beEditor();
        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                // Must not be empty.
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                // Must be bool.
                'selected' => 123,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                // Must be points of a circle.
                'selected' => true,
                'points' => [10, 20],
            ])
            ->assertStatus(422);

        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                'selected' => true,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(200);

        $a->refresh();
        $this->assertTrue($a->selected);
        $this->assertEquals([10, 20, 30], $a->points);

    }

    public function testUpdateTrainingProposals()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::instanceSegmentationId(),
        ]);
        $a = MaiaAnnotationTest::create([
            'job_id' => $job->id,
            'type_id' => Type::trainingProposalId(),
        ]);
        $this->beEditor();
        // Training proposals cannot be modified after the training proposal state.
        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                'selected' => true,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(422);
    }

    public function testUpdateAnnotationCandidates()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'state_id' => State::annotationCandidatesId(),
        ]);
        $a = MaiaAnnotationTest::create([
            'job_id' => $job->id,
            'type_id' => Type::annotationCandidateId(),
        ]);

        $this->beEditor();
        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                'selected' => true,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(200);
        // Selected annotation candidates cannot be updated.
        $this->putJson("/api/v1/maia-annotations/{$a->id}", [
                'selected' => false,
                'points' => [10, 20, 30],
            ])
            ->assertStatus(422);
    }
}
