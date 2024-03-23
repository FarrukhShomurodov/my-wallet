<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionStatisticsResource;
use App\Services\TransactionService;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $transaction = $this->transactionService->store($validated);
        return new JsonResponse([
            'status' => true,
            'message' => 'Transaction stored successfully',
            'data' => new TransactionResource($transaction)
        ], 200);
    }

    public function statistics($by): JsonResponse
    {
        $statistics = $this->transactionService->statistics($by);
        return new JsonResponse(['status' => true,
            'message' => 'Statistics fetched',
            'data' => TransactionStatisticsResource::collection($statistics)]);
    }

    public function statisticsBySubCategory(SubCategory $subCategory): JsonResponse
    {
        $statistics = $this->transactionService->statisticsBySubCategory($subCategory);
        return new JsonResponse([
            'status' => true,
            'message' => 'Statistics by category fetched',
            'data' => TransactionStatisticsResource::collection($statistics)
        ]);
    }
}
