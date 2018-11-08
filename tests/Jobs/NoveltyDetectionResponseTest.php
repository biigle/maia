<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;

class NoveltyDetectionResponseTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create();
        $image = ImageTest::create(['volume_id' => $job->volume_id]);

        $proposals = [$image->id => [[100, 200, 20, 0.9]]];

        $this->assertEquals(State::noveltyDetectionId(), $job->state_id);

        $response = new NoveltyDetectionResponse($job->id, $proposals);
        Queue::fake();
        $response->handle();

        $this->assertEquals(State::trainingProposalsId(), $job->fresh()->state_id);

        $annotations = $job->annotations()->trainingProposals()->get();
        $this->assertEquals(1, $annotations->count());
        $this->assertEquals([100, 200, 20], $annotations[0]->points);
        $this->assertEquals(0.9, $annotations[0]->score);
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
