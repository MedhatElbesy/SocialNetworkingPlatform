<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts;

        return view('user.profile', compact('user', 'posts'));
    }

}
