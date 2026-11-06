<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\AnnotationGuideline;
use Biigle\AnnotationGuidelineLabel;
use Biigle\Enums\Shape;
use Biigle\Jobs\ProcessAnnotatedImage;
use Biigle\Modules\Maia\Events\AnnotationCandidatesConverted;
use Biigle\Modules\Maia\Events\ConvertAnnotationCandidatesFailed;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\UserTest;
use Event;
use Exception;
use Queue;
use TestCase;

class ConvertAnnotationCandidatesTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create();
        $label = LabelTest::create();
        $user = UserTest::create();

        $c1 = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $label->id,
            'points' => [1, 2, 3],
        ]);

        $annotation = ImageAnnotationTest::create();
        $c2 = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $label->id,
            'annotation_id' => $annotation->id,
            'points' => [1, 2, 3],
        ]);

        Queue::fake();
        Event::fake([AnnotationCandidatesConverted::class]);

        (new ConvertAnnotationCandidates($job, $user))->handle();

        $a = $c1->fresh()->annotation;
        $this->assertNotNull($a);
        $this->assertSame([1, 2, 3], $a->points);
        $annotationLabel = $a->labels()->first();
        $this->assertSame($label->id, $annotationLabel->label_id);
        $this->assertSame($user->id, $annotationLabel->user_id);

        $this->assertSame($annotation->id, $c2->fresh()->annotation_id);

        Queue::assertPushed(ProcessAnnotatedImage::class, function ($job) use ($c1, $a) {
            $this->assertSame($c1->image_id, $job->file->id);
            $this->assertSame([$a->id], $job->only);

            return true;
        });

        $this->assertFalse($job->fresh()->convertingCandidates);
        Event::assertDispatched(function (AnnotationCandidatesConverted $event) use ($job, $user) {
            $this->assertSame($job->id, $event->id);
            $this->assertSame($user->id, $event->user->id);
            $this->assertFalse($event->hasUnconverted);

            return true;
        });
    }

    public function testHandleGuidelineLabel()
    {
        $job = MaiaJobTest::create();
        $user = UserTest::create();
        $allowed = LabelTest::create();
        $notAllowed = LabelTest::create();
        $guideline = AnnotationGuideline::factory()->create(['enforced' => true]);
        AnnotationGuidelineLabel::factory()->create([
            'annotation_guideline_id' => $guideline->id,
            'label_id' => $allowed->id,
        ]);

        $c1 = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $allowed->id,
            'points' => [1, 2, 3],
        ]);
        $c2 = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $notAllowed->id,
            'points' => [1, 2, 3],
        ]);

        Queue::fake();
        Event::fake([AnnotationCandidatesConverted::class]);

        (new ConvertAnnotationCandidates($job, $user, $guideline->id))->handle();

        $this->assertNotNull($c1->fresh()->annotation_id);
        $c2->refresh();
        $this->assertNull($c2->annotation_id);
        $this->assertSame($notAllowed->id, $c2->label_id);

        Event::assertDispatched(function (AnnotationCandidatesConverted $event) {
            return $event->hasUnconverted;
        });
    }

    public function testHandleGuidelineShape()
    {
        $job = MaiaJobTest::create();
        $user = UserTest::create();
        $label = LabelTest::create();
        $guideline = AnnotationGuideline::factory()->create([
            'enforced' => true,
            'only_shapes' => [Shape::POINT->value],
        ]);

        $c = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $label->id,
            'shape' => Shape::CIRCLE,
            'points' => [1, 2, 3],
        ]);

        Queue::fake();
        Event::fake([AnnotationCandidatesConverted::class]);

        (new ConvertAnnotationCandidates($job, $user, $guideline->id))->handle();

        $this->assertNull($c->fresh()->annotation_id);
        Event::assertDispatched(function (AnnotationCandidatesConverted $event) {
            return $event->hasUnconverted;
        });
    }

    public function testHandleGuidelineNotEnforced()
    {
        $job = MaiaJobTest::create();
        $user = UserTest::create();
        $label = LabelTest::create();
        // The guideline is no longer enforced since the candidates were submitted.
        $guideline = AnnotationGuideline::factory()->create([
            'enforced' => false,
            'only_shapes' => [Shape::POINT->value],
        ]);

        $c = AnnotationCandidateTest::create([
            'job_id' => $job->id,
            'label_id' => $label->id,
            'shape' => Shape::CIRCLE,
            'points' => [1, 2, 3],
        ]);

        Queue::fake();
        Event::fake([AnnotationCandidatesConverted::class]);

        (new ConvertAnnotationCandidates($job, $user, $guideline->id))->handle();

        $this->assertNotNull($c->fresh()->annotation_id);
        Event::assertDispatched(function (AnnotationCandidatesConverted $event) {
            return !$event->hasUnconverted;
        });
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $user = UserTest::create();

        Event::fake([ConvertAnnotationCandidatesFailed::class]);

        (new ConvertAnnotationCandidates($job, $user))->failed(new Exception);

        Event::assertDispatched(function (ConvertAnnotationCandidatesFailed $event) use ($job, $user) {
            $this->assertSame($job->id, $event->id);
            $this->assertSame($user->id, $event->user->id);

            return true;
        });
    }
}
