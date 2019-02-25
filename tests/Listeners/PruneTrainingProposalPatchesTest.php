<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use File;
use Storage;
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

        Storage::fake('test');
        config(['maia.training_proposal_storage_disk' => 'test']);
        $prefix = fragment_uuid_path($a->image->uuid);
        Storage::disk('test')->put("{$prefix}/{$a->id}.jpg", 'content');
        $prefix2 = fragment_uuid_path($a2->image->uuid);
        Storage::disk('test')->put("{$prefix2}/{$a2->id}.jpg", 'content');

        $listener = new PruneTrainingProposalPatches;
        $listener->handle(new MaiaJobContinued($a->job));
        $this->assertTrue(Storage::disk('test')->exists("{$prefix}/{$a->id}.jpg"));
        $this->assertFalse(Storage::disk('test')->exists("{$prefix2}/{$a2->id}.jpg"));
    }
}
