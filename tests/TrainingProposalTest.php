<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Shape;
use ModelTestCase;

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
        $this->assertSame([1, 2, 3, 4], $annotation->fresh()->points);
    }

    public function testScopeSelected()
    {
        $unselected = $this->model;
        $selected = self::create(['selected' => true]);
        $ids = TrainingProposal::selected()->pluck('id')->all();
        $this->assertSame([$selected->id], $ids);
    }

    public function testScopeUnselected()
    {
        $unselected = $this->model;
        $selected = self::create(['selected' => true]);
        $ids = TrainingProposal::unselected()->pluck('id')->all();
        $this->assertSame([$unselected->id], $ids);
    }

    public function testGetPoints()
    {
        $annotation = static::make(['points' => [1, 2]]);
        $this->assertSame([1, 2], $annotation->getPoints());
    }

    public function testGetShape()
    {
        $this->assertSame($this->model->shape, $this->model->getShape());
    }

    public function testGetFile()
    {
        $this->assertSame($this->model->image, $this->model->getFile());
    }
}
