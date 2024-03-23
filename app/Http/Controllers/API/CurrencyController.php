<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Services\CurrencyService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function fetchCurrency(): AnonymousResourceCollection
    {
        $currencies = $this->currencyService->get();
        return CurrencyResource::collection($currencies);
    }
}
