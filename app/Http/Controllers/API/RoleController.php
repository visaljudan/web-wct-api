<?php
//Api Done
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
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Roles"},
     *     summary="Get List roles Data",
     *     description="enter your roles here",
     *     operationId="roles",
     *     @OA\Response(
     *         response="default",
     *         description="return array model roles"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     tags={"Roles"},
     *     summary="roles",
     *     description="roles",
     *     operationId="Roles",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form roles",
     *          @OA\JsonContent(
     *            required={"role_name"},
     *              @OA\Property(property="role_name", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *        
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|unique:roles',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, "No Record Found", $validator->errors());
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $role = Role::create([
            'role_name' => $request->role_name,
        ]);

        $res = new RoleResource($role);
        return $this->sendSuccess(201, "Role created successfully", $res);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Detail",
     *     description="-",
     *     operationId="roles/GetById",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="return model admin"
     *     )
     * )
     */
    public function show($id)
    {
        $role = Role::find($id);


        if (!$role) {
            return $this->sendError(404, 'Role not found');
        }

        $res = new RoleResource($role);
        return $this->sendSuccess(200, "Role Found", $res);
    }
    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Update roles",
     *     description="-",
     *     operationId="roles/update",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="form admin",
     *          @OA\JsonContent(
     *             required={"role_name"},
     *              @OA\Property(property="role_name", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
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
            return $this->sendError(403, 'You are not allowed');
        }

        $role->role_name = $request->role_name;
        $role->save();
        $res = new RoleResource($role);
        return $this->sendSuccess(200, 'Role updated successfully', $res);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     tags={"Roles"},
     *     summary="Delete roles",
     *     description="-",
     *     operationId="roles/delete",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->sendError(404, 'Role not found');
        }

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        $role->delete();
        return $this->sendSuccess(200, 'Role deleted successfully');
    }
}
