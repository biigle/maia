<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidateFeatureVectors;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Jobs\ObjectDetectionResponse;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionComplete;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Notification;
use Queue;
use TestCase;

class ObjectDetectionResponseTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::objectDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $candidates = [[$image->id, 100, 200, 20, 0]];

        $response = new ObjectDetectionResponse($job->id, $candidates);
        Notification::fake();
        $response->handle();

        $this->assertEquals(State::annotationCandidatesId(), $job->fresh()->state_id);

        $annotations = $job->annotationCandidates()->get();
        $this->assertEquals(1, $annotations->count());
        $this->assertEquals([100, 200, 20], $annotations[0]->points);
        $this->assertEquals(0, $annotations[0]->score);
        $this->assertNull($annotations[0]->label_id);
        $this->assertNull($annotations[0]->annotation_id);
        $this->assertEquals($image->id, $annotations[0]->image_id);
        $this->assertEquals(Shape::circleId(), $annotations[0]->shape_id);

        Queue::assertPushed(GenerateImageAnnotationPatch::class);
        Queue::assertPushed(GenerateAnnotationCandidateFeatureVectors::class);
        Notification::assertSentTo($job->user, ObjectDetectionComplete::class);
    }

    public function testHandleWrongState()
    {
        $job = MaiaJobTest::create(['state_id' => State::failedObjectDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $candidates = [[$image->id, 100, 200, 20, 0]];

        $response = new ObjectDetectionResponse($job->id, $candidates);
        $response->handle();

        $this->assertEquals(State::failedObjectDetectionId(), $job->fresh()->state_id);

        $this->assertFalse($job->annotationCandidates()->exists());
        Queue::assertNotPushed(GenerateImageAnnotationPatch::class);
        Queue::assertNotPushed(GenerateAnnotationCandidateFeatureVectors::class);
    }

    public function testHandleRollback()
    {
        $job = MaiaJobTest::create(['state_id' => State::objectDetectionId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [[$image->id, 100, 200, 20, 0.9]];

        $response = new ObjectDetectionResponseStub($job->id, $proposals);
        try {
            $response->handle();
            $this->assertFalse(true);
        } catch (Exception $e) {
            //
        }

        $this->assertFalse($job->annotationCandidates()->exists());
        $this->assertEquals(State::objectDetectionId(), $job->fresh()->state_id);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create(['state_id' => State::objectDetectionId()]);
        $proposals = [[1, 100, 200, 20, 0.9]];

        $response = new ObjectDetectionResponse($job->id, $proposals);
        $response->failed(new Exception);
        Queue::assertPushed(ObjectDetectionFailure::class);
    }
}

class ObjectDetectionResponseStub extends ObjectDetectionResponse
{
    protected function updateJobState(MaiaJob $job)
    {
        throw new Exception('Something went wrong!');
    }
}
