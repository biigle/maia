<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidatePatches;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Illuminate\Support\Facades\Queue;
use TestCase;

class GenerateAnnotationCandidatePatchesTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJob::factory()->create();
        $tp = AnnotationCandidate::factory()->create(['job_id' => $job->id]);
        $j = new GenerateAnnotationCandidatePatches($job);
        $j->handle();
        Queue::assertPushed(GenerateImageAnnotationPatch::class);
    }

    public function testHandleEmpty()
    {
        $job = MaiaJob::factory()->create();
        $j = new GenerateAnnotationCandidatePatches($job);
        $j->handle();
        Queue::assertNotPushed(GenerateImageAnnotationPatch::class);
    }
}
