<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisteredCustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register/customer', [RegisteredCustomerController::class,'store']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'manager'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});