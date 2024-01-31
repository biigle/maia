<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\MaiaJob;
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

    public function testCascadeDelete()
    {
        $model = AnnotationCandidateFeatureVector::factory()->create();
        MaiaJob::find($model->job_id)->delete();
        $this->assertNull($model->fresh());
    }
}
