<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::apiResource('services', ServiceController::class);
Route::patch("services/{service}/change-status", [ServiceController::class, 'changeServiceStatus']);

// Customer routes
Route::get("customers/status", [CustomerController::class, 'getDataByStatus']);
Route::apiResource('customers', CustomerController::class);
Route::patch("customers/{customer}/change-status", [CustomerController::class, 'changeCustomerStatus']);
