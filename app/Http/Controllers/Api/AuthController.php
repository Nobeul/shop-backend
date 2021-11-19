<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return error_response($this->validationError, 'Validation error', $validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
    
            return success_response(200,'Registration successful',
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password
                ]
            );
        } catch(\Exception $e) {
            return error_response($e->getCode(), 'Exception', $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return error_response($this->validationError, 'Validation error', $validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $data['name'] = Auth::user()->name;
            $data['access_token'] = Auth::user()->createToken('accessToken')->accessToken;

            return success_response($this->success, 'You are successfully logged in.', $data);
        } else {
            return error_response($this->unauthorized, 'Unauthorized');
        }
    }

    public function logout()    {
        Auth::user()->token()->revoke();

        return response()->json(['message' => 'Logout successful'], $this->success);
    }
}
