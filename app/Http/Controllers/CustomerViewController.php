<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerViewController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('customer', compact('customers'));
    }
}
