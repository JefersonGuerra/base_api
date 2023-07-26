<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthController;

Route::post('/', [AuthController::class, 'login']);