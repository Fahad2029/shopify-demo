<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/hello', function () {
     return 'Laravel server is working';
});

Route::prefix('api')->group(function() {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/shopify/fetch-products', [ProductController::class, 'fetchFromShopify']);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
