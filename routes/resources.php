<?php

use App\Http\Controllers\Api\ResourceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('resources', ResourceController::class);
    Route::get('resources/{id}/availability', [ResourceController::class, 'checkAvailability']);
});