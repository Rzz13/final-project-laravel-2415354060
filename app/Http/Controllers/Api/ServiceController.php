<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::all();

        return response()->json([
            'success' => true,
            'message' => "Services retrieved successfully",
            'data' => $services,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $service = Service::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Service created successfully",
            'data' => $service,
        ], 201);
    }

    public function show(int $service): JsonResponse
    {
        $service = Service::find($service);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => "Service not found",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Service retrieved successfully",
            'data' => $service,
        ]);
    }

    public function update(Request $request, int $service): JsonResponse
    {
        $service = Service::find($service);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => "Service not found",
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'description' => ['string', 'nullable'],
            'status' => ['nullable', 'boolean'],
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Service updated successfully",
            'data' => $service,
        ]);
    }

    public function destroy(int $service): JsonResponse
    {
        $service = Service::find($service);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => "Service not found",
            ], 404);
        }

        if ($service->subscriptions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete service with active subscriptions",
            ], 400);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => "Service deleted successfully",
        ]);
    }

    public function getServiceById(int $service): JsonResponse
    {
        $service = Service::find($service);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => "Service not found",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Service retrieved successfully",
            'data' => $service,
        ]);
    }

    public function getServiceByStatus(Request $request): JsonResponse
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

        $service = Service::query()
            ->where('status', $status === 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Services with status '{$status}' retrieved successfully",
            'data' => $service,
        ]);
    }

    public function changeServiceStatus(int $service, Request $request): JsonResponse
    {
        $service = Service::find($service);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => "Service not found",
            ], 404);
        }

        $status = $request->query('status');

        if ($status === null) {
            return response()->json([
                'success' => false,
                'message' => "Status parameter is required",
            ], 400);
        }

        $service->update(['status' => $status === 'true']);

        return response()->json([
            'success' => true,
            'message' => "Service status updated successfully",
            'data' => $service,
        ]);
    }
}
