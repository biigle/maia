<?php

namespace Biigle\Tests\Modules\Maia\Policies;

use ApiTestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;

class MaiaJobPolicyTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->job = MaiaJobTest::create(['volume_id' => $this->volume()->id]);
    }

    public function testAccess()
    {
        $this->assertFalse($this->user()->can('access', $this->job));
        $this->assertFalse($this->guest()->can('access', $this->job));
        $this->assertTrue($this->editor()->can('access', $this->job));
        $this->assertTrue($this->expert()->can('access', $this->job));
        $this->assertTrue($this->admin()->can('access', $this->job));
        $this->assertTrue($this->globalAdmin()->can('access', $this->job));
    }

    public function testDestroy()
    {
        $this->assertFalse($this->user()->can('destroy', $this->job));
        $this->assertFalse($this->guest()->can('destroy', $this->job));
        $this->assertTrue($this->editor()->can('destroy', $this->job));
        $this->assertTrue($this->expert()->can('destroy', $this->job));
        $this->assertTrue($this->admin()->can('destroy', $this->job));
        $this->assertTrue($this->globalAdmin()->can('destroy', $this->job));
    }
}
