<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalPatches;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Illuminate\Support\Facades\Queue;
use TestCase;

class GenerateTrainingProposalPatchesTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create(['job_id' => $job->id]);
        $j = new GenerateTrainingProposalPatches($job);
        $j->handle();
        Queue::assertPushed(GenerateImageAnnotationPatch::class);
    }

    public function testHandleEmpty()
    {
        $job = MaiaJob::factory()->create();
        $j = new GenerateTrainingProposalPatches($job);
        $j->handle();
        Queue::assertNotPushed(GenerateImageAnnotationPatch::class);
    }
}
