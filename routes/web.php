<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api/')->namespace('API')->group(function () {
    Route::get('layout', 'LayoutController@layout');
    Route::get('pizza/{slug}', 'PizzaController@pizza');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('checkout', 'CheckoutController@checkout');
});
