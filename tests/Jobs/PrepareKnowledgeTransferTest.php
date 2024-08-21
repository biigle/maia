<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionFailed;
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
    public function testHandleDistance()
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
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Event::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Event::assertDispatched(MaiaJobContinued::class);
        $this->assertSame(1, $job->trainingProposals()->selected()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertSame($otherAnnotation->points, $proposal->points);
        $this->assertArrayHasKey('kt_scale_factors', $job->fresh()->params);
        $expect = [$otherImage->id => 0.25];
        $this->assertSame($expect, $job->fresh()->params['kt_scale_factors']);
    }

    public function testHandleArea()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
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
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Event::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Event::assertDispatched(MaiaJobContinued::class);
        $this->assertSame(1, $job->trainingProposals()->selected()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertSame($otherAnnotation->points, $proposal->points);
        $this->assertArrayHasKey('kt_scale_factors', $job->fresh()->params);
        $expect = [$otherImage->id => 0.25];
        $this->assertSame($expect, $job->fresh()->params['kt_scale_factors']);
    }

    public function testHandleAreaLaserpoints()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['laserpoints' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['laserpoints' => ['area' => 1]],
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
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        (new PrepareKnowledgeTransfer($job))->handle();
        $this->assertArrayHasKey('kt_scale_factors', $job->fresh()->params);
        $expect = [$otherImage->id => 0.25];
        $this->assertSame($expect, $job->fresh()->params['kt_scale_factors']);
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
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        (new PrepareKnowledgeTransfer($job))->handle();
        $this->assertSame(5, $job->trainingProposals()->selected()->count());
        $proposals = $job->trainingProposals;

        $this->assertSame(Shape::circleId(), $proposals[0]->shape_id);
        // Points get a default radius of 50 px.
        $this->assertSame([10, 20, 50], $proposals[0]->points);

        $this->assertSame(Shape::circleId(), $proposals[1]->shape_id);
        $this->assertSame([55, 55, 63.64], $proposals[1]->points);

        $this->assertSame(Shape::circleId(), $proposals[2]->shape_id);
        $this->assertSame([10, 20, 30], $proposals[2]->points);

        $this->assertSame(Shape::circleId(), $proposals[3]->shape_id);
        $this->assertSame([15, 15, 7.07], $proposals[3]->points);

        $this->assertSame(Shape::circleId(), $proposals[4]->shape_id);
        $this->assertSame([10, 15, 11.18], $proposals[4]->points);
    }

    public function testHandleDistanceMissingOtherVolume()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        $otherImage->volume->delete();

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(0, $job->trainingProposals()->count());
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleAreaMissingOtherVolume()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        $otherImage->volume->delete();

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(0, $job->trainingProposals()->count());
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleDistanceMissingOwnMetadata()
    {
        $ownImage = ImageTest::create();

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleAreaMissingOwnMetadata()
    {
        $ownImage = ImageTest::create();

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleDistanceMissingOtherMetadata()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create();

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleAreaMissingOtherMetadata()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create();

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
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
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleOwnAreaZero()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 0]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
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
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleOtherAreaZero()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 0]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleDistanceMissingOtherAnnotations()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['distance_to_ground' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }

    public function testHandleAreaMissingOtherAnnotations()
    {
        $ownImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 4]],
        ]);

        $otherImage = ImageTest::create([
            'attrs' => ['metadata' => ['area' => 1]],
        ]);

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => [
                'training_data_method' => 'area_knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
            ],
        ]);

        Notification::fake();
        (new PrepareKnowledgeTransfer($job))->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);
        $this->assertSame(State::failedObjectDetectionId(), $job->fresh()->state_id);
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
                'training_data_method' => 'knowledge_transfer',
                'kt_volume_id' => $otherImage->volume_id,
                'kt_restrict_labels' => [$ia->label_id],
            ],
        ]);

        (new PrepareKnowledgeTransfer($job))->handle();
        $this->assertSame(1, $job->trainingProposals()->selected()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertSame([1, 2, 3], $proposal->points);
    }
}
