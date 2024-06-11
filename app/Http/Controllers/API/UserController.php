<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Resources\User\UserResourceCollection;
use App\Http\Controllers\MainController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Swagger API Documentation",
 *     description="Swagger API documentation for Laravel application"
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",    
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="role", type="string", example="User")
 * )
 */

class UserController extends MainController
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users excluding Admins",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User found"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No Record Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=400
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No Record Found"
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();

        $user = $users->filter(function ($user) {
            return $user->role != 'Admin';
        });

        if (!Gate::allows('admin', User::class)) {
            return $this->sendError(403, 'You are not allowed');
        }

        if ($user->count() > 0) {
            $res = new UserResourceCollection($user);
            return $this->sendSuccess(200, 'User found', $res);
        } else {
            return $this->sendError(400, 'No Record Found');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Returns a user based on ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User found"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Record Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No Record Found"
     *             )
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $user = User::find($id);

        if (!$user || $user->role == 'Admin') {
            return $this->sendError(404, 'No Record Found');
        }

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        $res = new UserResource($user);
        return $this->sendSuccess(200, 'User found', $res);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Updates a user based on ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="username",
     *                 type="string",
     *                 example="newusername"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="newemail@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User updated successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=422
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation failed"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object"
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Deletes a user based on ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not allowed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=403
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You are not allowed"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError(404, 'User not found');
        }

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        $user->delete();
        return $this->sendSuccess(200, 'User deleted successfully');
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError(404, 'User not found');
        }

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError(400, 'Current password is incorrect');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $res = new UserResource($user);
        return $this->sendSuccess(200, 'Password updated successfully', $res);
    }

    public function newPassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError(404, 'User not found');
        }

        if (!Gate::allows('admin_userId', [$user, $id])) {
            return $this->sendError(403, 'You are not allowed');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $res = new UserResource($user);
        return $this->sendSuccess(200, 'Password updated successfully', $res);
    }

    /**
     * @OA\Get(
     *     path="/api/users/count/{role}",
     *     operationId="countUsersByRole",
     *     tags={"Users"},
     *     summary="Count users by role",
     *     description="Returns the total number of users with a specific role",
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Total users counted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Total {role}: {total}"
     *             )
     *         )
     *     )
     * )
     */
    public function countUser($role)
    {
        $totalUsers = User::where('role', $role)->count();

        return $this->sendSuccess(200, "Total $role: $totalUsers");
    }
}
