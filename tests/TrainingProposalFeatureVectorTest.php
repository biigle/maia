<?php

namespace Biigle\Tests\Modules\Maia;

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
}
