<?php

use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get("services/status", [ServiceController::class, 'getServiceByStatus']);
Route::apiResource('services', ServiceController::class);
Route::patch("services/{service}/change-status", [ServiceController::class, 'changeServiceStatus']);
