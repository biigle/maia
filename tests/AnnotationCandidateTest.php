<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Shape;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\LabelTest;
use ModelTestCase;

class AnnotationCandidateTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = AnnotationCandidate::class;

    public function testAttributes()
    {
        $label = LabelTest::create();
        $annotation = ImageAnnotationTest::create();
        $this->model->label_id = $label->id;
        $this->model->annotation_id = $annotation->id;
        $this->model->save();
        $this->model->refresh();
        $this->assertNotNull($this->model->image);
        $this->assertNotNull($this->model->shape);
        $this->assertNotNull($this->model->job);
        $this->assertNotNull($this->model->score);
        $this->assertNotNull($this->model->label);
        $this->assertNotNull($this->model->annotation);
        $this->assertNull($this->model->created_at);
        $this->assertNull($this->model->updated_at);
    }

    public function testCastPoints()
    {
        $annotation = static::create(['points' => [1, 2, 3, 4]]);
        $this->assertEquals([1, 2, 3, 4], $annotation->fresh()->points);
    }

    public function testGetPoints()
    {
        $annotation = static::make(['points' => [1, 2]]);
        $this->assertEquals([1, 2], $annotation->getPoints());
    }

    public function testGetShape()
    {
        $this->assertEquals($this->model->shape, $this->model->getShape());
    }

    public function testGetFile()
    {
        $this->assertEquals($this->model->image, $this->model->getFile());
    }
}
