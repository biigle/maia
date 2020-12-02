<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Tests\ImageAnnotationTest;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\UserTest;
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

        (new ConvertAnnotationCandidates($job, $user))->handle();

        $a = $c1->fresh()->annotation;
        $this->assertNotNull($a);
        $this->assertEquals([1, 2, 3], $a->points);
        $annotationLabel = $a->labels()->first();
        $this->assertEquals($label->id, $annotationLabel->label_id);
        $this->assertEquals($user->id, $annotationLabel->user_id);

        $this->assertEquals($annotation->id, $c2->fresh()->annotation_id);

        Queue::assertPushed(GenerateImageAnnotationPatch::class);
    }
}
