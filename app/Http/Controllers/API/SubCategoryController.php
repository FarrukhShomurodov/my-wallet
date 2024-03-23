<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Exception;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param SubCategoryRequest $request
     * @return JsonResponse
     */
    public function store(SubCategoryRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $validated = $request->validated();

            $subCategory = SubCategory::query()->create($validated);

            $user->subCategories()->attach($subCategory->id);

            return new JsonResponse([
                'status' => true,
                'message' => 'Sub category stored successfully',
                'data' => new SubCategoryResource($subCategory)
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubCategoryRequest $request
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function update(SubCategoryRequest $request, SubCategory $subCategory): JsonResponse
    {
        try {
            $user = auth()->user();

            $validated = $request->validated();

            if ($subCategory->is_default === 1) {
                $user->subCategories()->detach($subCategory->id);
                $subCategory = SubCategory::query()->create($validated);
                $user->subCategories()->attach($subCategory->id);
            } else {
                $subCategory->update($validated);
            }

            return new JsonResponse([
                'status' => true,
                'message' => 'Sub category stored successfully',
                'data' => new SubCategoryResource($subCategory)
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function destroy(SubCategory $subCategory): JsonResponse
    {
        try {
            $user = auth()->user();

            $user->subCategories()->detach($subCategory->id);

            if ($subCategory->is_default !== 1) {
                $subCategory->delete();
            }

            return new JsonResponse(['data' => 'Deleted successfully'], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
