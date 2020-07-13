<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Jobs\UseExistingAnnotations;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionFailed;
use Biigle\Shape;
use Biigle\Tests\AnnotationLabelTest;
use Biigle\Tests\AnnotationTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Illuminate\Support\Facades\Notification;
use Queue;
use TestCase;

class UseExistingAnnotationsTest extends TestCase
{
    public function testHandle()
    {
        $a = AnnotationTest::create(['shape_id' => Shape::circleId()]);
        $al1 = AnnotationLabelTest::create(['annotation_id' => $a->id]);
        $al2 = AnnotationLabelTest::create();
        $job = MaiaJobTest::create(['volume_id' => $a->image->volume_id]);

        Queue::fake();
        (new UseExistingAnnotations($job))->handle();
        Queue::assertPushed(GenerateAnnotationPatch::class);
        $this->assertEquals(1, $job->trainingProposals()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertEquals($a->points, $proposal->points);
    }

    public function testHandleRestrictLabels()
    {
        $a1 = AnnotationTest::create(['shape_id' => Shape::circleId()]);
        $al1 = AnnotationLabelTest::create(['annotation_id' => $a1->id]);
        // Create only one proposal even though the annotation has two matching labels.
        $al2 = AnnotationLabelTest::create([
            'annotation_id' => $a1->id,
            'label_id' => $al1->label_id,
        ]);
        $a2 = AnnotationTest::create([
            'shape_id' => Shape::circleId(),
            'image_id' => $a1->image_id,
        ]);
        $al3 = AnnotationLabelTest::create(['annotation_id' => $a2->id]);

        $job = MaiaJobTest::create([
            'volume_id' => $a1->image->volume_id,
            'params' => ['restrict_labels' => [$al1->label_id]],
        ]);

        (new UseExistingAnnotations($job))->handle();
        $this->assertEquals(1, $job->trainingProposals()->count());
        $proposal = $job->trainingProposals()->first();
        $this->assertEquals($a1->points, $proposal->points);
        $this->assertNull($proposal->score);
    }

    public function testHandleShapeConversion()
    {
        $a1 = AnnotationTest::create([
            'shape_id' => Shape::pointId(),
            'points' => [10, 20],
        ]);

        $a2 = AnnotationTest::create([
            'shape_id' => Shape::rectangleId(),
            'points' => [10, 10, 100, 10, 100, 100, 10, 100],
            'image_id' => $a1->image_id,
        ]);

        $a3 = AnnotationTest::create([
            'shape_id' => Shape::circleId(),
            'points' => [10, 20, 30],
            'image_id' => $a1->image_id,
        ]);

        $a4 = AnnotationTest::create([
            'shape_id' => Shape::lineId(),
            'points' => [10, 10, 20, 20],
            'image_id' => $a1->image_id,
        ]);

        $a5 = AnnotationTest::create([
            'shape_id' => Shape::polygonId(),
            'points' => [10, 10, 20, 20, 0, 20],
            'image_id' => $a1->image_id,
        ]);

        $job = MaiaJobTest::create(['volume_id' => $a1->image->volume_id]);

        (new UseExistingAnnotations($job))->handle();
        $this->assertEquals(5, $job->trainingProposals()->count());
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

    public function testHandleSkipNd()
    {
        $a = AnnotationTest::create();
        $job = MaiaJobTest::create([
            'volume_id' => $a->image->volume_id,
            'params' => ['use_existing' => true, 'skip_nd' => true],
        ]);

        Queue::fake();
        Notification::fake();
        (new UseExistingAnnotations($job))->handle();
        Queue::assertPushed(GenerateAnnotationPatch::class);
        Notification::assertSentTo($job->user, NoveltyDetectionComplete::class);
        $this->assertEquals(1, $job->trainingProposals()->count());
        $this->assertEquals(State::trainingProposalsId(), $job->fresh()->state_id);
    }

    public function testHandleSkipNdAndRestrictLabels()
    {
        $al = AnnotationLabelTest::create();
        $job = MaiaJobTest::create([
            'volume_id' => $al->annotation->image->volume_id,
            'params' => [
                'use_existing' => true,
                'skip_nd' => true,
                'restrict_labels' => [$al->label_id],
            ],
        ]);

        Queue::fake();
        Notification::fake();
        (new UseExistingAnnotations($job))->handle();
        $this->assertEquals(1, $job->trainingProposals()->count());
    }

    public function testHandleSkipNdNoAnnotations()
    {
        $job = MaiaJobTest::create([
            'params' => ['use_existing' => true, 'skip_nd' => true],
        ]);

        Queue::fake();
        Notification::fake();
        (new UseExistingAnnotations($job))->handle();
        Queue::assertNotPushed(GenerateAnnotationPatch::class);
        Notification::assertSentTo($job->user, NoveltyDetectionFailed::class);
        $this->assertEquals(0, $job->trainingProposals()->count());
        $this->assertEquals(State::failedNoveltyDetectionId(), $job->fresh()->state_id);
        $this->assertNotEmpty($job->error['message']);
    }
}
