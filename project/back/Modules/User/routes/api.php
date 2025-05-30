<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class)->names('user');
});
