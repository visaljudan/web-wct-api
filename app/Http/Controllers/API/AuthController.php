<?php
//Api Done
namespace App\Http\Controllers\API;

use App\Http\Controllers\MainController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends MainController
{
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     tags={"Auth"},
     *     summary="signup",
     *     description="-",
     *     operationId="signup",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form signup",
     *          @OA\JsonContent(
     *            required={"username", "email", "password"},
     *              @OA\Property(property="username", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/api/signin",
     *     tags={"Auth"},
     *     summary="signin",
     *     description="-",
     *     operationId="signin",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form signin",
     *          @OA\JsonContent(
     *            required={"username_email", "password"},
     *              @OA\Property(property="username_email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     )
     * )
     */
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

        if (!$user) {
            return $this->sendError(401, 'Invalid token or user not found', $tokenValue);
        }

        $user->update(['api_token' => null]);
        return $this->sendSuccess(200, 'User signed out successfully');
    }
    /**
     * @OA\Post(
     *     path="/api/google",
     *     tags={"Google"},
     *     summary="google",
     *     description="google",
     *     operationId="google",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form google",
     *          @OA\JsonContent(
     *            required={"username", "email", "profile"},
     *              @OA\Property(property="username", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="profile", type="url"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *        
     *     )
     * )
     */
    public function google(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|email',
            'profile' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, 'Validation failed', $validator->errors());
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            //Generate token
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            $user->update(['api_token' => $token]);

            // Return the user resource with success response
            $res = new UserResource($user);
            return $this->sendSuccess(200, 'User authenticated successfully', $res);
        } else {
            // If the user doesn't exist, it's a sign-up operation
            // Generate a secure random password
            $password = Str::random(16);
            $username = $request->username . rand(1000, 9999) . "";

            // Create a new user with the provided data
            $user = User::create([
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($password),
                'profile' => $request->profile,
            ]);

            // Generate a token for the user
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

            // Update the user's API token
            $user->update(['api_token' => $token]);

            // Return the user resource with success response
            $res = new UserResource($user);
            return $this->sendSuccess(200, 'User registered successfully', $res);
        }
    }
}
