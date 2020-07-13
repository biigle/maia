<?php

namespace Biigle\Tests\Modules\Maia\Policies;

use ApiTestCase;
use Biigle\Tests\LabelTest;
use Biigle\Tests\Modules\Maia\AnnotationCandidateTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\ProjectTest;

class AnnotationCandidatePolicyTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $this->annotation = AnnotationCandidateTest::create(['job_id' => $job->id]);
    }

    public function testAccess()
    {
        $this->assertFalse($this->user()->can('access', $this->annotation));
        $this->assertFalse($this->guest()->can('access', $this->annotation));
        $this->assertTrue($this->editor()->can('access', $this->annotation));
        $this->assertTrue($this->expert()->can('access', $this->annotation));
        $this->assertTrue($this->admin()->can('access', $this->annotation));
        $this->assertTrue($this->globalAdmin()->can('access', $this->annotation));
    }

    public function testUpdate()
    {
        $this->assertFalse($this->user()->can('update', $this->annotation));
        $this->assertFalse($this->guest()->can('update', $this->annotation));
        $this->assertTrue($this->editor()->can('update', $this->annotation));
        $this->assertTrue($this->expert()->can('update', $this->annotation));
        $this->assertTrue($this->admin()->can('update', $this->annotation));
        $this->assertTrue($this->globalAdmin()->can('update', $this->annotation));
    }

    public function testAttachLabel()
    {
        $allowedLabel = $this->labelChild();
        $disallowedLabel = LabelTest::create();

        // The volume belongs to this project, too, and the label is a valid one
        // for the project *but* no user belongs to the project so they shouldn't be able
        // to attach the label.
        $otherProject = ProjectTest::create();
        $otherProject->volumes()->attach($this->annotation->image->volume);
        $otherDisallowedLabel = LabelTest::create();
        $otherProject->labelTrees()->attach($otherDisallowedLabel->label_tree_id);

        $this->assertFalse($this->user()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertFalse($this->user()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertFalse($this->user()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));

        $this->assertFalse($this->guest()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertFalse($this->guest()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertFalse($this->guest()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));

        $this->assertTrue($this->editor()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertFalse($this->editor()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertFalse($this->editor()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));

        $this->assertTrue($this->expert()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertFalse($this->expert()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertFalse($this->expert()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));

        $this->assertTrue($this->admin()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertFalse($this->admin()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertFalse($this->admin()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));

        $this->assertTrue($this->globalAdmin()->can('attach-label', [$this->annotation, $allowedLabel]));
        $this->assertTrue($this->globalAdmin()->can('attach-label', [$this->annotation, $disallowedLabel]));
        $this->assertTrue($this->globalAdmin()->can('attach-label', [$this->annotation, $otherDisallowedLabel]));
    }
}
