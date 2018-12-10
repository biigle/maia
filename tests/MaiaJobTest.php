<?php

namespace Biigle\Tests\Modules\Maia;

use Event;
use ModelTestCase;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleted;

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

    public function testTrainingProposals()
    {
        $a = TrainingProposalTest::create(['job_id' => $this->model->id]);
        $this->assertEquals($a->id, $this->model->trainingProposals()->first()->id);
    }

    public function testAnnotationCandidates()
    {
        $a = AnnotationCandidateTest::create(['job_id' => $this->model->id]);
        $this->assertEquals($a->id, $this->model->annotationCandidates()->first()->id);
    }

    public function testCastsParams()
    {
        $this->model->params = ['test'];
        $this->model->save();
        $this->assertEquals(['test'], $this->model->fresh()->params);
    }

    public function testCastsError()
    {
        $this->model->error = ['message' => 'test'];
        $this->model->save();
        $this->assertEquals(['message' => 'test'], $this->model->fresh()->error);
    }

    public function testIsRunning()
    {
        $this->assertTrue($this->model->isRunning());
        $this->model->state_id = State::instanceSegmentationId();
        $this->assertTrue($this->model->isRunning());
        $this->model->state_id = State::annotationCandidatesId();
        $this->assertFalse($this->model->isRunning());
    }

    public function testIsFailed()
    {
        $this->assertFalse($this->model->hasFailed());
        $this->model->state_id = State::failedNoveltyDetectionId();
        $this->assertTrue($this->model->hasFailed());
        $this->model->state_id = State::failedInstanceSegmentationId();
        $this->assertTrue($this->model->hasFailed());
    }

    public function testRequiresAction()
    {
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::trainingProposalsId();
        $this->assertTrue($this->model->requiresAction());
        $this->model->state_id = State::instanceSegmentationId();
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::annotationCandidatesId();
        $this->assertFalse($this->model->requiresAction());
    }

    public function testDispatchesCreatedEvent()
    {
        Event::fake();
        static::create();
        Event::assertDispatched(MaiaJobCreated::class);
    }

    public function testDispatchesDeletedEvent()
    {
        Event::fake();
        $this->model->delete();
        Event::assertDispatched(MaiaJobDeleted::class);
    }
}
