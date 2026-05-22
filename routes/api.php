<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Service routes
Route::apiResource('services', ServiceController::class);
Route::patch("services/{service}/change-status", [ServiceController::class, 'changeServiceStatus']);

// Customer routes
Route::apiResource('customers', CustomerController::class);
Route::patch("customers/{customer}/change-status", [CustomerController::class, 'changeCustomerStatus']);

// Subscription routes
Route::get("subscriptions/{status}", [SubscriptionController::class, 'getSubscriptionByStatus']);
Route::apiResource('subscriptions', SubscriptionController::class);
