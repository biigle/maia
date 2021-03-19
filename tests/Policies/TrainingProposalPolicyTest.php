<?php

namespace Biigle\Tests\Modules\Maia\Policies;

use ApiTestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;

class TrainingProposalPolicyTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
        $this->annotation = TrainingProposalTest::create(['job_id' => $job->id]);
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
        $this->assertFalse($this->globalAdmin()->can('update', $this->annotation));
    }
}
