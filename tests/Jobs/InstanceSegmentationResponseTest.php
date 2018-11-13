<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationResponse;

class InstanceSegmentationResponseTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::instanceSegmentationId()]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $candidates = [$image->id => [[100, 200, 20, 0]]];

        $response = new InstanceSegmentationResponse($job->id, $candidates);
        Queue::fake();
        $response->handle();

        $this->assertEquals(State::annotationCandidatesId(), $job->fresh()->state_id);

        $annotations = $job->annotationCandidates()->get();
        $this->assertEquals(1, $annotations->count());
        $this->assertEquals([100, 200, 20], $annotations[0]->points);
        $this->assertEquals(0, $annotations[0]->score);
        $this->assertFalse($annotations[0]->selected);
        $this->assertEquals($image->id, $annotations[0]->image_id);
        $this->assertEquals(Shape::circleId(), $annotations[0]->shape_id);

        Queue::assertPushed(GenerateAnnotationPatch::class);
    }

    public function testHandleFailure()
    {
        $this->markTestIncomplete();
    }
}
