<?php

namespace App\Http\Controllers\Settings;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdatePassword;

class PasswordSettingsController extends Controller
{
    /**
     * Update the user's profile settings.
     *
     * @param  App\Http\Requests\Settings\UpdatePassword  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePassword $request)
    {
        $request->user()->update([
            'password' => bcrypt($request->input('password')),
        ]);

        flash('Password has been updated.');

        return redirect()->route('settings.index');
    }
}