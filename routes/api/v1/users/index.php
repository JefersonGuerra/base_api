<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/', [UserController::class, 'post']);
    Route::get('/', [UserController::class, 'get']);
    Route::put('/', [UserController::class, 'put']);
    Route::delete('/', [UserController::class, 'delete']);
});
