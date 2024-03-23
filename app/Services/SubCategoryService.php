<?php

namespace App\Services;

use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Storage;

class SubCategoryService
{
    public function store(array $validated)
    {
        return SubCategory::query()->create($validated);
    }

    public function update(array $validated, SubCategory $subCategory)
    {
        $user = auth()->user();

        if ($subCategory->is_default === 1) {
            $user->subCategories()->detach($subCategory->id);
            $subCategory = SubCategory::query()->create($validated);
            $user->subCategories()->attach($subCategory->id);
        } else {
            $subCategory->update($validated);
        }

        return new SubCategoryResource($subCategory);
    }

    public function destroy(SubCategory $subCategory)
    {
        if ($subCategory->is_default !== 1) {
            $this->deleteIcon($subCategory->icon_url);
            $subCategory->delete();
        }

        return true;
    }

    public function uploadIcon($file)
    {
        $iconUrl = $file->store('icon_url', 'public');
        return env('APP_URL') . '/storage/' . $iconUrl;
    }

    public function deleteIcon($iconUrl)
    {
        if (Storage::disk('public')->exists($iconUrl)) {
            Storage::disk('public')->delete($iconUrl);
        }
    }
}
