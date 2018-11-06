<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;

class MaiaControllerTest extends ApiTestCase
{
    public function testStore()
    {
        $id = $this->volume()->id;
        $this->doTestApiRoute('POST', "/api/v1/volumes/{$id}/maia");

        $this->beGuest();
        $this->postJson("/api/v1/volumes/{$id}/maia")->assertStatus(403);

        $this->beEditor();
        // mssing arguments
        $this->postJson("/api/v1/volumes/{$id}/maia")->assertStatus(422);

        // patch size must be an odd number
        $this->postJson("/api/v1/volumes/{$id}/maia", [
            'clusters' => 5,
            'patch_size' => 40,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ])->assertStatus(422);

        $params = [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
        ];

        $this->postJson("/api/v1/volumes/{$id}/maia", $params)->assertStatus(200);

        $job = MaiaJob::first();
        $this->assertNotNull($job);
        $this->assertEquals($id, $job->volume_id);
        $this->assertEquals($this->editor()->id, $job->user_id);
        $this->assertEquals(State::noveltyDetectionId(), $job->state_id);
        $this->assertEquals($params, $job->params);

        // only one running job at a time
        $this->postJson("/api/v1/volumes/{$id}/maia", $params)->assertStatus(422);
    }
}
