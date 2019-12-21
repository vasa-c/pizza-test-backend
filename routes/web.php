<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api/')->namespace('API')->group(function () {
    Route::get('layout', 'LayoutController@layout');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
});
