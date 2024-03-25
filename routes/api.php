<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('sign-up', ['App\Http\Controllers\AuthController', 'signUp']);
    Route::post('token', ['App\Http\Controllers\AuthController', 'getToken']);
    Route::post('logout', ['App\Http\Controllers\AuthController', 'logout'])->middleware('auth:sanctum');
});

Route::apiResource('todos', TodoController::class)->middleware('auth:sanctum');
