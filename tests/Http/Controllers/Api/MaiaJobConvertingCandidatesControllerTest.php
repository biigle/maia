<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;

class MaiaJobConvertingCandidatesControllerTest extends ApiTestCase
{
    public function testGet()
    {
        $job = MaiaJobTest::create([
            'volume_id' => $this->volume()->id,
            'attrs' => ['converting_candidates' => true],
        ]);

        $this->doTestApiRoute('GET', "/api/v1/maia-jobs/{$job->id}/converting-candidates");

        $this->beGuest();
        $this->getJson("/api/v1/maia-jobs/{$job->id}/converting-candidates")
            ->assertStatus(403);

        $this->beEditor();
        $this->getJson("/api/v1/maia-jobs/{$job->id}/converting-candidates")
            ->assertStatus(200);

        $job->convertingCandidates = false;
        $job->save();

        $this->getJson("/api/v1/maia-jobs/{$job->id}/converting-candidates")
            ->assertStatus(404);
    }
}
