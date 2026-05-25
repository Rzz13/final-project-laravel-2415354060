<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;

class SubscriptionViewController extends Controller
{
    public function index()
    {
        $subscriptions  = Subscription::with(['customer', 'service'])->latest()->get();
        $allCustomers   = Customer::where('status', true)->orderBy('name')->get(['id', 'name']);
        $allServices    = Service::where('status', true)->orderBy('name')->get(['id', 'name']);

        return view('subcription', compact('subscriptions', 'allCustomers', 'allServices'));
    }
}
