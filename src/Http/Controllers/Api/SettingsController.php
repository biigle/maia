<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Update the user settings for MAIA jobs.
     *
     * @api {post} users/my/settings/maia Update the user settings for MAIA jobs
     * @apiGroup Users
     * @apiName StoreUsersMaiaSettings
     * @apiPermission user
     *
     * @apiParam (Optional arguments) {String} maia_notifications Set to `'email'` or `'web'` to receive notifications for MAIA jobs either via email or the BIIGLE notification center.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (config('maia.notifications.allow_user_settings') === false) {
            abort(404);
        }
        $this->validate($request, [
            'maia_notifications' => 'filled|in:email,web',
        ]);
        $settings = $request->only(['maia_notifications']);
        $request->user()->setSettings($settings);
    }
}
