<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\Shape;
use TestCase;

class TrainingProposalFeatureVectorTest extends TestCase
{
    public function testAttributes()
    {
        $model = TrainingProposalFeatureVector::factory()->create();
        $this->assertNotNull($model->job_id);
        $this->assertNotNull($model->vector);
    }

    public function testCascadeDelete()
    {
        $model = TrainingProposalFeatureVector::factory()->create();
        MaiaJob::find($model->job_id)->delete();
        $this->assertNull($model->fresh());
    }
}
