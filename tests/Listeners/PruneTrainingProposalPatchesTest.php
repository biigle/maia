<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use File;
use TestCase;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Listeners\PruneTrainingProposalPatches;

class PruneTrainingProposalPatchesTest extends TestCase
{
    public function testHandle()
    {
        $a = TrainingProposalTest::create(['selected' => true]);

        $a2 = TrainingProposalTest::create([
            'job_id' => $a->job_id,
            'selected' => false,
        ]);

        // Only unselected training proposal patches should be deleted.
        File::shouldReceive('delete')->once()->with($a2->getPatchPath());

        $listener = new PruneTrainingProposalPatches;
        $listener->handle(new MaiaJobContinued($a->job));
    }
}
