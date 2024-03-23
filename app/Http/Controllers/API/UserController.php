<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetCurrencyRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @return UserResource
     */
    public function get(): UserResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * @param SetCurrencyRequest $request
     * @return JsonResponse
     */
    public function setCurrency(SetCurrencyRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();

            $user = Auth::user();
            $user->currency_id = $validated['currency_id'];
            $user->save();

            return new JsonResponse([
                'status' => true,
                'message' => 'Currency set successfully',
                'data' => UserResource::make($user)
            ]);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}
