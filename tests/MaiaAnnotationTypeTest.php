<?php

namespace Biigle\Tests\Modules\Maia;

use ModelTestCase;
use Biigle\Modules\Maia\MaiaAnnotationType;

class MaiaAnnotationTypeTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = MaiaAnnotationType::class;

    public function testAttributes()
    {
        $this->assertNotNull($this->model->name);
        $this->assertNull($this->model->created_at);
        $this->assertNull($this->model->updated_at);
    }

    public function testTrainingProposalId()
    {
        $this->assertNotNull(MaiaAnnotationType::trainingProposalId());
    }

    public function testAnnotationCandidateId()
    {
        $this->assertNotNull(MaiaAnnotationType::annotationCandidateId());
    }
}
