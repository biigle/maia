<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationFailed;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Biigle\Shape;
use Biigle\Tests\ImageAnnotationLabelTest;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Event;
use Illuminate\Support\Facades\Notification;
use Queue;
use TestCase;

class PrepareKnowledgeTransferTest extends TestCase
{
    public function testHandle()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $ownAnnotation = ImageAnnotationTest::create([
            'shape_id' => Shape::circleId(),
            'points' => [1, 2, 3],
            'image_id' => $ownImage->id,
        ]);

        $otherAnnotation = ImageAnnotationTest::create([
            'shape_id' => Shape::circleId(),
            'points' => [4, 5, 6],
            'image_id' => $otherImage->id,
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Event::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Event::assertDispatched(MaiaJobContinued::class);
        $this->assertEquals(1, $job->trainingProposals()->selected()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertEquals($otherAnnotation->points, $proposal->points);
        $this->assertArrayHasKey('kt_scale_factor', $job->fresh()->params);
        $this->assertEquals(0.25, $job->fresh()->params['kt_scale_factor']);
    }

    public function testHandleShapeConversion()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $a1 = ImageAnnotationTest::create([
            'shape_id' => Shape::pointId(),
            'points' => [10, 20],
            'image_id' => $otherImage->id,
        ]);

        $a2 = ImageAnnotationTest::create([
            'shape_id' => Shape::rectangleId(),
            'points' => [10, 10, 100, 10, 100, 100, 10, 100],
            'image_id' => $a1->image_id,
        ]);

        $a3 = ImageAnnotationTest::create([
            'shape_id' => Shape::circleId(),
            'points' => [10, 20, 30],
            'image_id' => $a1->image_id,
        ]);

        $a4 = ImageAnnotationTest::create([
            'shape_id' => Shape::lineId(),
            'points' => [10, 10, 20, 20],
            'image_id' => $a1->image_id,
        ]);

        $a5 = ImageAnnotationTest::create([
            'shape_id' => Shape::polygonId(),
            'points' => [10, 10, 20, 20, 0, 20],
            'image_id' => $a1->image_id,
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        (new PrepareKnowledgeTransfer($job))->handle();
        $this->assertEquals(5, $job->trainingProposals()->selected()->count());
        $proposals = $job->trainingProposals;

        $this->assertEquals(Shape::circleId(), $proposals[0]->shape_id);
        // Points get a default radius of 50 px.
        $this->assertEquals([10, 20, 50], $proposals[0]->points);

        $this->assertEquals(Shape::circleId(), $proposals[1]->shape_id);
        $this->assertEquals([55, 55, 63.64], $proposals[1]->points);

        $this->assertEquals(Shape::circleId(), $proposals[2]->shape_id);
        $this->assertEquals([10, 20, 30], $proposals[2]->points);

        $this->assertEquals(Shape::circleId(), $proposals[3]->shape_id);
        $this->assertEquals([15, 15, 7.07], $proposals[3]->points);

        $this->assertEquals(Shape::circleId(), $proposals[4]->shape_id);
        $this->assertEquals([10, 15, 11.18], $proposals[4]->points);
    }

    public function testHandleMissingOtherVolume()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        $otherImage->volume->delete();

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(0, $job->trainingProposals()->count());
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleMissingOwnMetadata()
    {
        $ownImage = ImageTest::create();

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleMissingOtherMetadata()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create();

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleOwnDistanceZero()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 0]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleOtherDistanceZero()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 0]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleMissingOtherAnnotations()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => ['kt_volume_id' => $otherImage->volume_id],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleRestrictLabels()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $ia = ImageAnnotationLabelTest::create([
            'annotation_id' => ImageAnnotationTest::create([
                'shape_id' => Shape::circleId(),
                'points' => [1, 2, 3],
                'image_id' => $otherImage->id,
            ])->id,
        ]);

        $ia2 = ImageAnnotationLabelTest::create([
            'annotation_id' => ImageAnnotationTest::create([
                'shape_id' => Shape::circleId(),
                'points' => [4, 5, 6],
                'image_id' => $otherImage->id,
            ])->id,
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'kt_volume_id' => $otherImage->volume_id,
                'kt_restrict_labels' => [$ia->label_id],
            ],
        ]);

        (new PrepareKnowledgeTransfer($job))->handle();
        $this->assertEquals(1, $job->trainingProposals()->selected()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertEquals([1, 2, 3], $proposal->points);
    }
}
