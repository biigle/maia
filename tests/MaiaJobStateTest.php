<?php

namespace Biigle\Tests\Modules\Maia;

use ModelTestCase;
use Biigle\Modules\Maia\MaiaJobState;

class MaiaJobStateTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = MaiaJobState::class;

    public function testAttributes()
    {
        $this->assertNotNull($this->model->name);
        $this->assertNull($this->model->created_at);
        $this->assertNull($this->model->updated_at);
    }

    public function testNoveltyDetectionId()
    {
        $this->assertNotNull(MaiaJobState::$noveltyDetectionId);
    }

    public function testTrainingProposalsId()
    {
        $this->assertNotNull(MaiaJobState::$trainingProposalsId);
    }

    public function testInstanceSegmentationId()
    {
        $this->assertNotNull(MaiaJobState::$instanceSegmentationId);
    }

    public function testAnnotationCandidatesId()
    {
        $this->assertNotNull(MaiaJobState::$annotationCandidatesId);
    }
}
