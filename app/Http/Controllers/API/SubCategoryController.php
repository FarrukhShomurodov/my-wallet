<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

            if (isset($validated['icon_url'])) {
                $iconUrl = $request->file('icon_url')->store('icon_url', 'public');
                $validated['icon_url'] = env('APP_URL') . '/storage/' . $iconUrl;
            }

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

            if (isset($validated['icon_url'])) {
                Storage::disk('public')->delete($subCategory->icon_url);
                $iconUrl = $request->file('icon_url')->store('icon_url', 'public');
                $validated['icon_url'] = env('APP_URL') . '/storage/' . $iconUrl;
            }

            if ($subCategory->is_default === 1) {
                $user->subCategories()->detach($subCategory->id);
                $subCategory = SubCategory::query()->create($validated);
                $user->subCategories()->attach($subCategory->id);
            } else {
                $subCategory->update($validated);
            }

            return new JsonResponse([
                'status' => true,
                'message' => 'Sub category updated successfully',
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

            if ($subCategory->is_default !== 1 && Storage::disk('public')->exists($subCategory->icon_url)) {
                Storage::disk('public')->delete($subCategory->icon_url);
            }


            return new JsonResponse(['data' => 'Sub category deleted successfully'], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
