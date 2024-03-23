<?php

namespace App\Services;

use App\Models\Currency;

class CurrencyService
{

    public function get()
    {
        return Currency::query()->get();
    }
}
