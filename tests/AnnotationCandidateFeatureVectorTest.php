<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Shape;
use TestCase;

class AnnotationCandidateFeatureVectorTest extends TestCase
{
    public function testAttributes()
    {
        $model = AnnotationCandidateFeatureVector::factory()->create();
        $this->assertNotNull($model->job_id);
        $this->assertNotNull($model->vector);
    }
}
