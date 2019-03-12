<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Exception;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Illuminate\Support\Facades\Notification;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;

class NoveltyDetectionResponseTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [[$image->id, 100, 200, 20, 0.9]];

        $response = new NoveltyDetectionResponse($job->id, $proposals);
        Queue::fake();
        Notification::fake();
        $response->handle();

        $this->assertEquals(State::trainingProposalsId(), $job->fresh()->state_id);

        $annotations = $job->trainingProposals()->get();
        $this->assertEquals(1, $annotations->count());
        $this->assertEquals([100, 200, 20], $annotations[0]->points);
        $this->assertEquals(0.9, $annotations[0]->score);
        $this->assertFalse($annotations[0]->selected);
        $this->assertEquals($image->id, $annotations[0]->image_id);
        $this->assertEquals(Shape::circleId(), $annotations[0]->shape_id);

        Queue::assertPushed(GenerateAnnotationPatch::class);
        Notification::assertSentTo($job->user, NoveltyDetectionComplete::class);
    }

    public function testHandleLimit()
    {
        Notification::fake();
        config(['maia.training_proposal_limit' => 1]);
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [[$image->id, 100, 200, 20, 0.9], [$image->id, 100, 200, 20, 1.9]];

        $response = new NoveltyDetectionResponse($job->id, $proposals);
        $response->handle();

        $annotations = $job->trainingProposals()->get();
        $this->assertEquals(1, $annotations->count());
        $this->assertEquals(1.9, $annotations[0]->score);
    }

    public function testHandleWrongState()
    {
        $job = MaiaJobTest::create(['state_id' => State::failedNoveltyDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [[$image->id, 100, 200, 20, 0]];

        $response = new NoveltyDetectionResponse($job->id, $proposals);
        Queue::fake();
        $response->handle();

        $this->assertEquals(State::failedNoveltyDetectionId(), $job->fresh()->state_id);

        $this->assertFalse($job->trainingProposals()->exists());
        Queue::assertNotPushed(GenerateAnnotationPatch::class);
    }

    public function testHandleRollback()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [[$image->id, 100, 200, 20, 0.9]];

        $response = new NoveltyDetectionResponseStub($job->id, $proposals);
        try {
            $response->handle();
            $this->assertFalse(true);
        } catch (Exception $e) {
            //
        }

        $this->assertFalse($job->trainingProposals()->exists());
        $this->assertEquals(State::noveltyDetectionId(), $job->fresh()->state_id);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $proposals = [[1, 100, 200, 20, 0.9]];

        $response = new NoveltyDetectionResponse($job->id, $proposals);
        Queue::fake();
        $response->failed(new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
    }
}

class NoveltyDetectionResponseStub extends NoveltyDetectionResponse
{
    protected function updateJobState(MaiaJob $job)
    {
        throw new Exception('Something went wrong!');
    }
}
