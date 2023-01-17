<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductAssetsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);
Route::get('/product-with-asset', [ProductsController::class, 'product_with_asset']);
Route::post('/add-product', [ProductsController::class, 'store']);
Route::put('/products/{id}', [ProductsController::class, 'update']);
Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
Route::get('/product-shorting-price', [ProductsController::class, 'shorting_price']);

Route::get('/product-assets', [ProductAssetsController::class, 'index']);
Route::get('/product-assets/{id}', [ProductAssetsController::class, 'show']);
Route::post('/add-product-assets', [ProductAssetsController::class, 'store']);
Route::delete('/product-assets/{id}', [ProductAssetsController::class, 'destroy']);

Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/short-categories', [CategoriesController::class, 'short_categories']);




