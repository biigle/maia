<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatchChunk;
use Biigle\Modules\Maia\Listeners\PruneTrainingProposalPatches;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Queue;
use TestCase;

class PruneTrainingProposalPatchesTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        $a = TrainingProposalTest::create(['selected' => true]);

        $a2 = TrainingProposalTest::create([
            'job_id' => $a->job_id,
            'selected' => false,
        ]);

        config(['maia.training_proposal_storage_disk' => 'test']);
        $prefix = fragment_uuid_path($a2->image->uuid);
        $file = "{$prefix}/{$a2->id}.jpg";

        $listener = new PruneTrainingProposalPatches;
        $listener->handle(new MaiaJobContinued($a->job));
        Queue::assertPushed(DeleteAnnotationPatchChunk::class, function ($job) use ($file) {
            return $job->disk === 'test' && count($job->files) === 1 && $job->files[0] === $file;
        });
    }
}
