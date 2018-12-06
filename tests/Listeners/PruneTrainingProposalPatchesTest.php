<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use File;
use TestCase;
use Biigle\Modules\Maia\MaiaAnnotationType;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Listeners\PruneTrainingProposalPatches;

class PruneTrainingProposalPatchesTest extends TestCase
{
    public function testHandle()
    {
        $a = MaiaAnnotationTest::create([
            'type_id' => MaiaAnnotationType::trainingProposalId(),
            'selected' => true,
        ]);

        $a2 = MaiaAnnotationTest::create([
            'job_id' => $a->job_id,
            'type_id' => MaiaAnnotationType::trainingProposalId(),
            'selected' => false,
        ]);

        $a3 = MaiaAnnotationTest::create([
            'job_id' => $a->job_id,
            'type_id' => MaiaAnnotationType::annotationCandidateId(),
            'selected' => false,
        ]);

        // Only unselected training proposal patches should be deleted.
        File::shouldReceive('delete')->once()->with($a2->getPatchPath());

        $listener = new PruneTrainingProposalPatches;
        $listener->handle(new MaiaJobContinued($a->job));
    }
}
