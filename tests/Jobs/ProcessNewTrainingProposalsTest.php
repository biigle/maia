<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Jobs\ProcessNoveltyDetectedImage;
use Biigle\Modules\Maia\Jobs\ProcessNewTrainingProposals;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Illuminate\Support\Facades\Queue;
use TestCase;

class ProcessNewTrainingProposalsTest extends TestCase
{
    public function testHandle()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $tp = TrainingProposal::factory()->create([
            'job_id' => $job->id,
            'image_id' => $image->id,
        ]);
        $j = new ProcessNewTrainingProposals($tp->job);
        $j->handle();
        Queue::assertPushed(ProcessNoveltyDetectedImage::class);
    }

    public function testHandleEmpty()
    {
        $image = Image::factory()->create();
        $job = MaiaJob::factory()->create(['volume_id' => $image->volume_id]);
        $j = new ProcessNewTrainingProposals($job);
        $j->handle();
        Queue::assertNotPushed(ProcessNoveltyDetectedImage::class);
    }
}
