<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the user profile for the specified username.
     *
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::whereUsername($username)->with('privacy')->firstOrFail();
        $topics = $user->topics()->with(['posts', 'user'])->latest()->paginate(15);

        return view('users.show', compact('topics', 'user'));
    }
}
