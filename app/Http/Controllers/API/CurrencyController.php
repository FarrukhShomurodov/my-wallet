<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CurrencyController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function fetchCurrency(): AnonymousResourceCollection
    {
        $currencies = Currency::query()->get();
        return CurrencyResource::collection($currencies);
    }
}
