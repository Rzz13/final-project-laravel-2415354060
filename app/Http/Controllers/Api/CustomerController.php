<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::latest()->get();
        return response()->json([
            'success' => true,
            'message' => "Customers retrieved successfully",
            'data' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'text'],
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Customer created successfully",
            'data' => $customer,
        ], 201);
    }
}
