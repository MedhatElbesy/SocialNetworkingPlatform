<?php

namespace App\Repositories\user;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getAllUsers(Request $request);

    public function getUserById($id);

    public function updateUserProfile($request, $id);

    public function getTimeline();
}
