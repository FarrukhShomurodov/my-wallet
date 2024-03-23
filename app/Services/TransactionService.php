<?php

namespace App\Services;

use App\Http\Resources\TransactionStatisticsResource;
use App\Models\SubCategory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    public function store($validated)
    {
        $validated['user_id'] = Auth::user()->id;

        return Transaction::query()->create($validated);
    }

    public function statistics($by)
    {
        $user = Auth::user();
        $subCategories = $user->subCategories;

        if ($subCategories->isEmpty()) {
            return ['message' => 'No subcategories found for the user'];
        }

        $categoryIds = $subCategories->pluck('id');

        $startDate = Carbon::now();
        $endDate = Carbon::now();

        switch ($by) {
            case "daily":
                $startDate->startOfDay();
                $endDate->endOfDay();
                break;
            case "weekly":
                $startDate->startOfWeek();
                $endDate->endOfWeek();
                break;
            case "monthly":
                $startDate->startOfMonth();
                $endDate->endOfMonth();
                break;
            case "yearly":
                $startDate->startOfYear();
                $endDate->endOfYear();
                break;
            default:
                return ['error' => 'Select date time'];
        }

        return Transaction::whereIn('sub_category_id', $categoryIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, sub_category_id, count(*) as transactions, sum(amount) as total_amount')
            ->groupBy('date', 'sub_category_id')
            ->get();
    }

    public function statisticsBySubCategory(SubCategory $subCategory)
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        return Transaction::where('sub_category_id', $subCategory->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, sub_category_id, count(*) as transactions, sum(amount) as total_amount')
            ->groupBy('year', 'sub_category_id')
            ->get();
    }
}
