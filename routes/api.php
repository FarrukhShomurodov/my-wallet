<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    // User
    Route::get('/user', [UserController::class, 'get']);
    Route::post('/set-currency', [UserController::class, 'setCurrency']);

    // Currency
    Route::get('/currency', [CurrencyController::class, 'fetchCurrency']);

    // SubCategory
    Route::group(['prefix' => 'sub-category'], function () {
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::put('/{subCategory}', [SubCategoryController::class, 'update']);
        Route::delete('/{subCategory}', [SubCategoryController::class, 'destroy']);
    });

    // Transaction
    Route::group(['prefix' => 'transaction'], function (){
      Route::post('/make', [TransactionController::class, 'store']);
      Route::get('/{by}',[TransactionController::class, 'statistics']);
      Route::get('by-sub-category/{subCategory}',[TransactionController::class, 'statisticsBySubCategory']);
    });
});
