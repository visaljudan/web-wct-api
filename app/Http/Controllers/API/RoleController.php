<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Role\RoleResourceCollection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RoleController extends MainController
{
    //index
    public function index()
    {
        $roles = Role::all();
        if ($roles->count() > 0) {
            $res = new RoleResourceCollection($roles);
            return $this->sendSuccess(200, "Role Found", $res);
        } else {
            return $this->sendError(400, "No Record Found");
        }
    }

    //Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|unique:roles',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, "No Record Found", $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $role = Role::create([
            'role_name' => $request->role_name,
        ]);

        $res = new RoleResource($role);
        return $this->sendSuccess(201, "Role created successfully", $res);
    }


    public function show($id)
    {
        $role = Role::find($id);


        if (!$role) {
            return $this->sendError(404, 'Role not found');
        }

        $res = new RoleResource($role);
        return $this->sendSuccess(200, "Role Found", $res);
    }

    //Update
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->sendError(404, 'Role not found');
        }

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $role->role_name = $request->role_name;
        $role->save();
        $res = new RoleResource($role);
        return $this->sendSuccess(200, 'Role updated successfully', $res);
    }

    //Destroy
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->sendError(404, 'Role not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed', !Gate::allows('admin'));
        }

        $role->delete();
        return $this->sendSuccess(200, 'Role deleted successfully');
    }
}
