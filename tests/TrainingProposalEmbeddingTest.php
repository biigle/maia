<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\TrainingProposalEmbedding;
use Biigle\Shape;
use TestCase;

class TrainingProposalEmbeddingTest extends TestCase
{
    public function testAttributes()
    {
        $model = TrainingProposalEmbedding::factory()->create();
        $this->assertNotNull($model->embedding);
    }
}
