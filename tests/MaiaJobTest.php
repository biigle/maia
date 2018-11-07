<?php

namespace Biigle\Tests\Modules\Maia;

use Event;
use ModelTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Listeners\MaiaJobCreated;

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

    public function testCastsParams()
    {
        $this->model->params = ['test'];
        $this->model->save();
        $this->assertEquals(['test'], $this->model->fresh()->params);
    }

    public function testIsRunning()
    {
        $this->assertTrue($this->model->isRunning());
        $this->model->state_id = State::finishedId();
        $this->assertFalse($this->model->isRunning());
    }

    public function testRequiresAction()
    {
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::trainingProposalsId();
        $this->assertTrue($this->model->requiresAction());
        $this->model->state_id = State::instanceSegmentationId();
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::annotationCandidatesId();
        $this->assertTrue($this->model->requiresAction());
        $this->model->state_id = State::finishedId();
        $this->assertFalse($this->model->requiresAction());
    }

    public function testDispatchesCreatedEvent()
    {
        Event::fake();
        static::create();
        Event::assertDispatched(MaiaJobCreated::class);
    }
}
