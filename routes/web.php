<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CanBeLogined;

Route::prefix('/api/')->namespace('API')->group(function () {
    Route::get('layout', 'LayoutController@layout');
    Route::get('pizza/{slug}', 'PizzaController@pizza');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('checkout', 'CheckoutController@checkout');

    Route::prefix('cabinet')->middleware(CanBeLogined::class)->group(function () {
        Route::get('', 'CabinetController@cabinet');
        Route::get('{number}', 'CabinetController@order')->where('number', '[0-9]+');
    });
});
