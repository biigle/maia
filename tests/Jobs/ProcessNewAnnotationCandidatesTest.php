<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use Biigle\Modules\Maia\Jobs\ProcessNewAnnotationCandidates;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Illuminate\Support\Facades\Queue;
use TestCase;

class ProcessNewAnnotationCandidatesTest extends TestCase
{
    public function testHandle()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $tp = AnnotationCandidate::factory()->create([
            'job_id' => $job->id,
            'image_id' => $image->id,
        ]);
        $j = new ProcessNewAnnotationCandidates($tp->job);
        $j->handle();
        Queue::assertPushed(ProcessObjectDetectedImage::class);
    }

    public function testHandleEmpty()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $j = new ProcessNewAnnotationCandidates($job);
        $j->handle();
        Queue::assertNotPushed(ProcessObjectDetectedImage::class);
    }
}
