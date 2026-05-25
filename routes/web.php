<?php

use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\ServiceViewController;
use App\Http\Controllers\SubscriptionViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('web.customers'))->name('home');
/* Halaman Customers */
Route::get('/customers', [CustomerViewController::class, 'index'])->name('web.customers');

/* Halaman Services */
Route::get('/services', [ServiceViewController::class, 'index'])->name('web.services');

/* Halaman Subscriptions */
Route::get('/subscriptions', [SubscriptionViewController::class, 'index'])->name('web.subscriptions');

Route::get('/users', fn() => abort(404))->name('web.users');

Route::post('/logout', fn() => redirect('/'))->name('logout');
