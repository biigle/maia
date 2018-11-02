<?php

namespace Biigle\Tests\Modules\Maia;

use ModelTestCase;
use Biigle\Modules\Maia\MaiaJob;

class MaiaJobTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = MaiaJob::class;

    public function testAttributes()
    {
        $this->assertNotNull($this->model->volume);
        $this->assertNotNull($this->model->user);
        $this->assertNotNull($this->model->created_at);
        $this->assertNotNull($this->model->updated_at);
        $this->assertNotNull($this->model->state);
    }

    public function testAnnotations()
    {
        $annotation = MaiaAnnotationTest::create(['job_id' => $this->model->id]);
        $this->assertEquals($annotation->id, $this->model->annotations()->first()->id);
    }
}
