<?php

namespace Biigle\Tests\Modules\Maia;

use ModelTestCase;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;
use Biigle\Modules\Maia\MaiaAnnotation as Annotation;

class MaiaAnnotationTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = Annotation::class;

    public function testAttributes()
    {
        $this->assertNotNull($this->model->image);
        $this->assertNotNull($this->model->shape);
        $this->assertNotNull($this->model->type);
        $this->assertNotNull($this->model->job);
        $this->assertNotNull($this->model->score);
        $this->assertNotNull($this->model->selected);
        $this->assertNull($this->model->created_at);
        $this->assertNull($this->model->updated_at);
    }

    public function testScopeTrainingProposals()
    {
        $proposal = self::create(['type_id' => Type::$trainingProposalId]);
        $candidate = self::create(['type_id' => Type::$annotationCandidateId]);
        $ids = Annotation::trainingProposals()->pluck('id')->all();
        $this->assertEquals([$proposal->id], $ids);
    }

    public function testScopeAnnotationCandidates()
    {
        $proposal = self::create(['type_id' => Type::$trainingProposalId]);
        $candidate = self::create(['type_id' => Type::$annotationCandidateId]);
        $ids = Annotation::annotationCandidates()->pluck('id')->all();
        $this->assertEquals([$candidate->id], $ids);
    }

    public function testScopeUnselected()
    {
        $unselected = $this->model;
        $selected = self::create(['selected' => true]);
        $ids = Annotation::unselected()->pluck('id')->all();
        $this->assertEquals([$unselected->id], $ids);
    }
}
