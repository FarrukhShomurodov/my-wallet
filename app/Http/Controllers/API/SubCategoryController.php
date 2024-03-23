<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Services\SubCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    protected SubCategoryService $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function store(SubCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('icon_url')) {
                $iconUrl = $this->uploadIcon($request->file('icon_url'));
                $validated['icon_url'] = $iconUrl;
            }

            $subCategory = $this->subCategoryService->store($validated);
            return response()->json([
                'status' => true,
                'message' => 'Sub category stored successfully',
                'data' => new SubCategoryResource($subCategory)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory): JsonResponse
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('icon_url')) {
                $this->subCategoryService->deleteIcon($subCategory->icon_url);
                $iconUrl = $this->subCategoryService->uploadIcon($request->file('icon_url'));
                $validated['icon_url'] = $iconUrl;
            }

            $subCategory = $this->subCategoryService->update($validated, $subCategory);
            return response()->json([
                'status' => true,
                'message' => 'Sub category updated successfully',
                'data' => new SubCategoryResource($subCategory)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(SubCategory $subCategory): JsonResponse
    {
        try {
            $this->subCategoryService->destroy($subCategory);
            return response()->json(['data' => 'Sub category deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
