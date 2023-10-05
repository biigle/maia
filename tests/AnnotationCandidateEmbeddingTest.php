<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\AnnotationCandidateEmbedding;
use Biigle\Shape;
use TestCase;

class AnnotationCandidateEmbeddingTest extends TestCase
{
    public function testAttributes()
    {
        $model = AnnotationCandidateEmbedding::factory()->create();
        $this->assertNotNull($model->embedding);
    }
}
