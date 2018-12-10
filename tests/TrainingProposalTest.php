<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Shape;
use ModelTestCase;
use Biigle\Modules\Maia\TrainingProposal;

class TrainingProposalTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = TrainingProposal::class;

    public function testAttributes()
    {
        $this->model->refresh();
        $this->assertNotNull($this->model->image);
        $this->assertNotNull($this->model->shape);
        $this->assertNotNull($this->model->job);
        $this->assertNotNull($this->model->score);
        $this->assertNotNull($this->model->selected);
        $this->assertNull($this->model->created_at);
        $this->assertNull($this->model->updated_at);
    }

    public function testCastPoints()
    {
        $annotation = static::create(['points' => [1, 2, 3, 4]]);
        $this->assertEquals([1, 2, 3, 4], $annotation->fresh()->points);
    }

    public function testScopeSelected()
    {
        $unselected = $this->model;
        $selected = self::create(['selected' => true]);
        $ids = TrainingProposal::selected()->pluck('id')->all();
        $this->assertEquals([$selected->id], $ids);
    }

    public function testScopeUnselected()
    {
        $unselected = $this->model;
        $selected = self::create(['selected' => true]);
        $ids = TrainingProposal::unselected()->pluck('id')->all();
        $this->assertEquals([$unselected->id], $ids);
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
        $this->assertEquals("testpath/{$this->model->job_id}/p-{$this->model->id}.jpg", $this->model->getPatchPath());
    }
}
