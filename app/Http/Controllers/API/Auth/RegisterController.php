<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
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

            $user = User::query()->create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);


            $token = $user->createToken("TOKEN")->plainTextToken;

            // SetDefaultSubCategories
            $defaultCategories = SubCategory::query()->where('is_default', 1)->get();
            $user->subCategories()->attach($defaultCategories);

            return new JsonResponse([
                'status' => true,
                'message' => 'User Created Successfully',
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
}
