<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Listeners\PruneTrainingProposalFeatureVectors;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use TestCase;

class PruneTrainingProposalFeatureVectorsTest extends TestCase
{
    public function testHandle()
    {
        $a = TrainingProposalTest::create(['selected' => true]);
        $af = TrainingProposalFeatureVector::factory()->create([
            'id' => $a->id,
            'job_id' => $a->job_id,
        ]);

        $a2 = TrainingProposalTest::create([
            'job_id' => $a->job_id,
            'selected' => false,
        ]);
        $af2 = TrainingProposalFeatureVector::factory()->create([
            'id' => $a2->id,
            'job_id' => $a2->job_id,
        ]);


        $listener = new PruneTrainingProposalFeatureVectors;
        $listener->handle(new MaiaJobContinued($a->job));
        $this->assertNotNull($af->fresh());
        $this->assertNull($af2->fresh());
    }
}
