<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * User register
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            Log::info('Store request received for creating a new user', [
                'request_data' => $request->all()
            ]);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            if ($validator->fails()) {
                $response = [
                    'status' => 'failed',
                    'message' => $validator->getMessageBag()
                ];

                return response()->json($response, 400, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $token = auth('api')->login($user);

            return $this->respondWithToken($token);

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a user: ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
