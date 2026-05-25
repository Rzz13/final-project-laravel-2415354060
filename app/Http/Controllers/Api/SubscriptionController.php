<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $query = Subscription::query()->with(['customer', 'service']);

        $subscriptions = $query->latest()->get();

        // Load only data without relationships
        $subscriptions->makeHidden(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscriptions retrieved successfully',
            'data' => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', 'string', Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])], // 
        ]);

        $subscription = Subscription::query()->create($data);
        $subscription->load(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'data' => $subscription,
        ], 201);
    }

    public function show(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()->with(['customer', 'service'])->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors' => [],
            ], 404);
        }

        $subscription->makeHidden(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription retrieved successfully',
            'data' => $subscription,
        ]);
    }

    public function getSubscriptionByStatus(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $allowedStatus = ['active', 'inactive', 'trial', 'isolir', 'dismantle'];

        if (!in_array($status, $allowedStatus, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'status' => ['The selected status is invalid.']
                ]
            ], 422);
        }

        $subscriptions = Subscription::query()->with(['customer', 'service'])->where('status', $status)->latest()->get();
        $subscriptions->makeHidden(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => "Subscriptions with status '{$status}' retrieved successfully",
            'data' => $subscriptions,
        ]);
    }

    public function changeSubscriptionStatus(int $subscription, Request $request): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors' => [],
            ], 404);
        }

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])],
        ]);

        // VALIDATE STATUS CANNOT CHANGE TO OTHER STATUS IF ALREADY DISMANTLE
        if ($subscription->status === 'dismantle' && $data['status'] !== 'dismantle') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status from dismantle to other status',
                'errors' => [],
            ], 400);
        }

        $subscription->update(['status' => $data['status']]);
        $subscription->makeHidden(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription status updated successfully',
            'data' => $subscription,
        ]);
    }
}
