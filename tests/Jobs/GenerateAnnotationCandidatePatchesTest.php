<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidatePatches;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Illuminate\Support\Facades\Queue;
use TestCase;

class GenerateAnnotationCandidatePatchesTest extends TestCase
{
    public function testHandle()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $tp = AnnotationCandidate::factory()->create([
            'job_id' => $job->id,
            'image_id' => $image->id,
        ]);
        $j = new GenerateAnnotationCandidatePatches($tp->job);
        $j->handle();
        Queue::assertPushed(ProcessObjectDetectedImage::class, function ($job) {
            $this->assertTrue($job->skipFeatureVectors);

            return true;
        });
    }

    public function testHandleEmpty()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $j = new GenerateAnnotationCandidatePatches($job);
        $j->handle();
        Queue::assertNotPushed(ProcessObjectDetectedImage::class);
    }
}
