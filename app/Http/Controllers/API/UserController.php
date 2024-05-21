<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User\UserResourceCollection;
use App\Http\Controllers\MainController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

class UserController extends MainController
{

    //Index
    public function index()
    {
        $users = User::all();

        $user = $users->filter(function ($user) {
            return $user->role != 'Admin';
        });

        if ($user->count() > 0) {

            $res = new UserResourceCollection($user);
            return $this->sendSuccess(200, 'User found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }


    //Show
    public function show($id)
    {

        $user = User::find($id);

        if (!$user || $user->role == 'Admin') {
            return $this->sendError(404, 'No Record Found');
        }

        $res = new UserResource($user);
        return $this->sendSuccess(200, 'User found', $res);
    }

    //Update
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError(404, 'User not found');
        }

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user->username = $request->input('username');
        $user->email = $request->input('email');

        $user->save();

        $res = new UserResource($user);
        return $this->sendSuccess(200, 'User updated successfully', $res);
    }

    //Destroy
    public function destroy($id)
    {
        $user = User::find($id);

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        if (!$user) {
            return $this->sendError(404, 'User not found');
        }

        $user->delete();
        return $this->sendSuccess(200, 'User deleted successfully');
    }
}
