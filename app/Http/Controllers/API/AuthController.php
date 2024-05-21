<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends MainController
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        $user->api_token = $token;
        $user->save();

        $user = User::find($user->id);

        $res = new UserResource($user);

        return $this->sendSuccess(200, 'User registered successfully', $res);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username_email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user = User::where('email', $request->username_email)
            ->orWhere('username', $request->username_email)
            ->first();

        if (!$user) {
            return $this->sendError(401, 'Username or Email not found!');
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError(401, 'Wrong Password!');
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        $user->update(['api_token' => $token]);


        $res = new UserResource($user);

        return $this->sendSuccess(200, 'User authenticated successfully', $res);
    }

    public function signout(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return $this->sendError(401, 'Token is missing in the request header');
        }

        $tokenValue = str_replace('Bearer ', '', $token);

        $user = User::where('api_token', $tokenValue)->first();

        if ($user) {
            $user->update(['api_token' => null]);
            return $this->sendSuccess(200, 'User signed out successfully');
        }

        return $this->sendError(401, 'Invalid token or user not found', $tokenValue);
    }
}
