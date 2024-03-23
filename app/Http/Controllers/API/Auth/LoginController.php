<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Incorrect username or password.',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken("TOKEN")->plainTextToken;

            return new JsonResponse([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => UserResource::make($user),
                    'token' => $token
                ]
            ], 200);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();

            $user->tokens()->delete();

            return new JsonResponse([
                'status' => true,
                'message' => 'User logged out successfully'
            ], 200);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
