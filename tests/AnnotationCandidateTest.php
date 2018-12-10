<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Shape;
use ModelTestCase;
use Biigle\Tests\LabelTest;
use Biigle\Tests\AnnotationTest;
use Biigle\Modules\Maia\AnnotationCandidate;

class AnnotationCandidateTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = AnnotationCandidate::class;

    public function testAttributes()
    {
        $label = LabelTest::create();
        $annotation = AnnotationTest::create();
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

    public function testGetImage()
    {
        $this->assertEquals($this->model->image, $this->model->getImage());
    }

    public function testGetPatchPath()
    {
        config(['maia.patch_storage' => 'testpath']);
        $this->assertEquals("testpath/{$this->model->job_id}/c-{$this->model->id}.jpg", $this->model->getPatchPath());
    }
}
