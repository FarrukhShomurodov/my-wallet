<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionStatisticsResource;
use App\Models\SubCategory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request): TransactionResource
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        $transaction = Transaction::query()->create($validated);

        return TransactionResource::make($transaction);
    }

    public function statistics($by): JsonResponse
    {
        $user = Auth::user();
        $subCategories = $user->subCategories;

        if ($subCategories->isEmpty()) {
            return new JsonResponse(['message' => 'No subcategories found for the user'], 404);
        }

        $categoryIds = $subCategories->pluck('id');

        if ($by == "daily") {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else if ($by == "weekly") {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } else if ($by == "monthly") {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else if ($by == "yearly") {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } else {
            return new JsonResponse([
                'status' => false,
                'error' => 'Select date time'
            ],);
        }
        $statistics = Transaction::whereIn('sub_category_id', $categoryIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, sub_category_id, count(*) as transactions, sum(amount) as total_amount')
            ->groupBy('date', 'sub_category_id')
            ->get();

        return new JsonResponse([
            'status' => true,
            'message' => 'Statistics fetched',
            'data' => TransactionStatisticsResource::collection($statistics)
        ], 200);
    }

    public function statisticsBySubCategory(SubCategory $subCategory): JsonResponse
    {

        // Получаем дату начала и конца текущего года
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        // Получаем статистику для выбранной подкатегории пользователя за текущий год
        $statistics = Transaction::where('sub_category_id', $subCategory->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, sub_category_id, count(*) as transactions, sum(amount) as total_amount')
            ->groupBy('year', 'sub_category_id') // Группируем по году и подкатегории
            ->get();

        return new JsonResponse([
            'status' => true,
            'message' => 'Statistics by category, fetched',
            'data' => TransactionStatisticsResource::collection($statistics)
        ], 200);
    }
}
