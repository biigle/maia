<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Listeners\DeleteAnnotationFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use TestCase;

class DeleteAnnotationFeatureVectorsTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJob::factory()->create();
        $tp = TrainingProposalFeatureVector::factory()->create([
            'job_id' => $job->id,
        ]);
        $ac = AnnotationCandidateFeatureVector::factory()->create([
            'job_id' => $job->id,
        ]);

        $event = new MaiaJobDeleting($job);
        (new DeleteAnnotationFeatureVectors)->handle($event);
        $this->assertNull($tp->fresh());
        $this->assertNull($ac->fresh());
    }
}
