<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'DESC')->get();
        if (empty($users)) {
            return error_response($this->notFound, 'User not found');
        }
        return success_response($this->success, 'User found successfully', UserResource::collection($users));
    }

    public function showUserByEmail(Request $request)
    {
        $user = User::where(['email' => $request->email])->first();
        if (empty($user)) {
            return error_response($this->notFound, 'User not found');
        }
        return success_response($this->success, 'User found successfully', $user);
    }
}
