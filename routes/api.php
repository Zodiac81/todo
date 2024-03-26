<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('sign-up', ['App\Http\Controllers\AuthController', 'signUp'])->name('user.signup');
    Route::post('token', ['App\Http\Controllers\AuthController', 'getToken'])->name('user.getToken');
    Route::post('logout', ['App\Http\Controllers\AuthController', 'logout'])->name('user.logout')->middleware('auth:sanctum');
});

Route::apiResource('todos', TodoController::class)->except('show')->middleware('auth:sanctum');
