<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatchChunk;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatches;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Queue;
use TestCase;

class DeleteAnnotationPatchesTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        config(['maia.training_proposal_storage_disk' => 'test']);
        config(['maia.annotation_candidate_storage_disk' => 'test2']);

        $tp = TrainingProposalTest::create();
        $ac = AnnotationCandidateTest::create(['job_id' => $tp->job_id]);

        $tpPrefix = fragment_uuid_path($tp->image->uuid);
        $tpFile = "{$tpPrefix}/{$tp->id}.jpg";
        $acPrefix = fragment_uuid_path($ac->image->uuid);
        $acFile = "{$acPrefix}/{$ac->id}.jpg";

        (new DeleteAnnotationPatches($tp->job))->handle();
        Queue::assertPushed(DeleteAnnotationPatchChunk::class, function ($job) use ($tpFile) {
            return $job->disk === 'test' && $job->files[0] = $tpFile;
        });

        Queue::assertPushed(DeleteAnnotationPatchChunk::class, function ($job) use ($acFile) {
            return $job->disk === 'test2' && count($job->files) === 1 && $job->files[0] === $acFile;
        });
    }
}
