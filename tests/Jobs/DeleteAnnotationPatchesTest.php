<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Storage;
use TestCase;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatches;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;

class DeleteAnnotationPatchesTest extends TestCase
{
    public function testHandle()
    {
        Storage::fake('test');
        Storage::fake('test2');
        config(['maia.training_proposal_storage_disk' => 'test']);
        config(['maia.annotation_candidate_storage_disk' => 'test2']);

        $tp = TrainingProposalTest::create();
        $ac = AnnotationCandidateTest::create(['job_id' => $tp->job_id]);

        $tpPrefix = fragment_uuid_path($tp->image->uuid);
        $acPrefix = fragment_uuid_path($ac->image->uuid);

        Storage::disk('test')->put("{$tpPrefix}/{$tp->id}.jpg", 'content');
        Storage::disk('test2')->put("{$acPrefix}/{$ac->id}.jpg", 'content');

        (new DeleteAnnotationPatches($tp->job))->handle();

        $this->assertFalse(Storage::disk('test')->exists("{$tpPrefix}/{$tp->id}.jpg"));
        $this->assertFalse(Storage::disk('test2')->exists("{$acPrefix}/{$ac->id}.jpg"));
    }
}
