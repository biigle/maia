<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Api;

use ApiTestCase;

class SettingsControllerTest extends ApiTestCase
{
    public function testStore()
    {
        $response = $this->json('POST', 'api/v1/users/my/settings/maia')
            ->assertStatus(401);

        $this->beUser();
        $response = $this->post('api/v1/users/my/settings/maia')
            ->assertStatus(200);

        $this->assertNull($this->user()->fresh()->settings);

        $response = $this->post('api/v1/users/my/settings/maia', [
                'unknown_key' => 'somevalue',
            ])
            ->assertStatus(200);

        $this->assertNull($this->user()->fresh()->settings);
    }

    public function testStoreNotificationSettings()
    {
        $user = $this->user();
        $this->beUser();

        $this->assertNull($this->user()->fresh()->getSettings('maia_notifications'));

        $response = $this->json('POST', 'api/v1/users/my/settings/maia', [
                'maia_notifications' => 'unknown value',
            ])
            ->assertStatus(422);

        $this->assertNull($this->user()->fresh()->getSettings('maia_notifications'));

        $response = $this->json('POST', 'api/v1/users/my/settings/maia', [
                'maia_notifications' => 'email',
            ])
            ->assertStatus(200);

        $this->assertEquals('email', $this->user()->fresh()->getSettings('maia_notifications'));

        $response = $this->json('POST', 'api/v1/users/my/settings/maia', [
                'maia_notifications' => 'web',
            ])
            ->assertStatus(200);

        $this->assertEquals('web', $this->user()->fresh()->getSettings('maia_notifications'));

        config(['maia.notifications.allow_user_settings' => false]);

        $response = $this->json('POST', 'api/v1/users/my/settings/maia', [
                'maia_notifications' => 'email',
            ])
            ->assertStatus(404);

        $this->assertEquals('web', $this->user()->fresh()->getSettings('maia_notifications'));
    }
}
