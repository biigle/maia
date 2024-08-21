<?php

namespace Biigle\Tests\Modules\Maia\Http\Requests;

use ApiTestCase;

class UpdateUserSettingsTest extends ApiTestCase
{
    public function testUpdate()
    {
        $this->beUser();
        $this->putJson("/api/v1/users/my/settings", ['maia_notifications' => 'test'])
            ->assertStatus(422);

        $this->assertNull($this->user()->fresh()->getSettings('maia_notifications'));

        $this->putJson("/api/v1/users/my/settings", ['maia_notifications' => 'email'])
            ->assertStatus(200);

        $this->assertSame('email', $this->user()->fresh()->getSettings('maia_notifications'));

        $this->putJson("/api/v1/users/my/settings", ['maia_notifications' => 'web'])
            ->assertStatus(200);

        $this->assertSame('web', $this->user()->fresh()->getSettings('maia_notifications'));
    }
}
