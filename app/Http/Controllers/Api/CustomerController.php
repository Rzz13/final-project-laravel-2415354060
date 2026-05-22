<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $query = Customer::query();

        $customers = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Customers retrieved successfully',
            'data' => $customers,
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'string', 'unique:customers,customer_id'],
            'name'        => ['required', 'string'],
            'email'       => ['nullable', 'string', 'email', 'unique:customers,email'],
            'phone'       => ['nullable', 'string'],
            'address'     => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        $data['status'] = $data['status'] ?? true;

        $customer = Customer::query()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer,
        ], 201);
    }

    public function show(int $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully',
            'data' => $customer,
        ]);
    }

    public function update(Request $request, int $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors' => [],
            ], 404);
        }

        $data = $request->validate([
            'customer_id' => ['sometimes', 'string', 'unique:customers,customer_id,' . $customer->id],
            'name'        => ['sometimes', 'string'],
            'email'       => ['nullable', 'string', 'email', 'unique:customers,email,' . $customer->id],
            'phone'       => ['nullable', 'string'],
            'address'     => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }

    public function destroy(int $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors' => [],
            ], 404);
        }

        // Proteksi relasi: Jika customer memiliki data subscription, jangan diperbolehkan hapus
        if ($customer->subscriptions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Customer cannot be deleted because it has subscriptions',
                'errors' => [],
            ], 422);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
            'data' => null,
        ]);
    }

    public function getCustomerByStatus(Request $request): JsonResponse
    {
        $status = $request->query('status');

        if ($status === null) {
            return response()->json([
                'success' => false,
                'message' => "Status parameter is required",
            ], 400);
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'status' => ['The selected status is invalid.']
                ]
            ], 422);
        }

        $customers = Customer::query()
            ->where('status', $status === 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Customers with status '{$status}' retrieved successfully",
            'data' => $customers,
        ]);
    }

    public function changeCustomerStatus(int $customer, Request $request): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors' => [],
            ], 404);
        }

        $activate = $request->query('activate');

        if ($activate === null) {
            return response()->json([
                'success' => false,
                'message' => "Activate parameter is required",
            ], 400);
        }

        $customer->update(['status' => $activate === 'true']);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully',
            'data' => $customer,
        ]);
    }
}
