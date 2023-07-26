<?php
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(  __DIR__ . "/users/index.php" );
Route::prefix('login')->group(  __DIR__ . "/login/index.php" );