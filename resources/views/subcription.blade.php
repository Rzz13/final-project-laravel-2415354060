@extends('app')

@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions')

@section('content')

    {{-- BUTTON --}}
    <div class="flex justify-end mb-5">

        <button data-open-modal id="btn-add-subscription"
            class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold
                   px-4 py-2.5 rounded-lg hover:bg-gray-700 active:scale-95 transition-all">

            <i class='bx bx-plus text-base'></i>
            Add Data

        </button>

    </div>

    {{-- MODAL TEMPLATE --}}
    <template id="erp-modal-tpl" data-title="Add Subscription">

        <form data-endpoint="/api/subscriptions" data-method="POST" class="space-y-5">

            {{-- CUSTOMER --}}
            <div>

                <label class="block text-sm font-semibold text-gray-800 mb-1.5">
                    Customer
                </label>

                <div class="relative">

                    <select name="customer_id"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300 appearance-none">

                        <option value="" disabled selected>
                            Select Customer
                        </option>

                        @foreach ($allCustomers as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                        @endforeach

                    </select>

                    <i
                        class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>

                </div>

            </div>

            {{-- SERVICE --}}
            <div>

                <label class="block text-sm font-semibold text-gray-800 mb-1.5">
                    Service
                </label>

                <div class="relative">

                    <select name="service_id"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300 appearance-none">

                        <option value="" disabled selected>
                            Select Service
                        </option>

                        @foreach ($allServices as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }}
                            </option>
                        @endforeach

                    </select>

                    <i
                        class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>

                </div>

            </div>

            {{-- DATE --}}
            <div class="grid grid-cols-2 gap-4">

                <div>

                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">
                        Start Date
                    </label>

                    <input type="date" name="start_date"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300">

                </div>

                <div>

                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">
                        End Date
                    </label>

                    <input type="date" name="end_date"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300">

                </div>

            </div>

            {{-- STATUS --}}
            <div>

                <label class="block text-sm font-semibold text-gray-800 mb-1.5">
                    Status
                </label>

                <div class="relative">

                    <select name="status"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300 appearance-none">

                        <option value="" disabled selected>
                            Select Status
                        </option>

                        <option value="active">Active</option>
                        <option value="trial">Trial</option>
                        <option value="isolir">Isolir</option>
                        <option value="dismantle">Dismantle</option>
                        <option value="inactive">Inactive</option>

                    </select>

                    <i
                        class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>

                </div>

            </div>

        </form>

    </template>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-visible">

        <table class="w-full border-collapse">

            <thead>

                <tr class="border-b border-gray-200">

                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Customer Name
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Services
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Services Period
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Status
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide w-[120px]">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($subscriptions as $sub)
                    @php

                        $subStatus = strtolower($sub->status);

                        $allActions = [
                            'active' => [
                                'icon' => 'bx-key',
                                'label' => 'Active',
                                'action' => 'status:active',
                            ],

                            'inactive' => [
                                'icon' => 'bx-power-off',
                                'label' => 'Deactivate',
                                'action' => 'status:inactive',
                            ],

                            'trial' => [
                                'icon' => 'bx-time',
                                'label' => 'Trial',
                                'action' => 'status:trial',
                            ],

                            'isolir' => [
                                'icon' => 'bx-block',
                                'label' => 'Isolir',
                                'action' => 'status:isolir',
                            ],

                            'dismantle' => [
                                'icon' => 'bx-x-circle',
                                'label' => 'Dismantle',
                                'action' => 'status:dismantle',
                                'danger' => true,
                            ],
                        ];

                        $availableActions = array_values(
                            array_filter($allActions, fn($key) => $key !== $subStatus, ARRAY_FILTER_USE_KEY),
                        );

                        $statusMap = [
                            'active' => [
                                'label' => 'Active',
                                'class' => 'bg-green-100 text-green-700',
                            ],

                            'trial' => [
                                'label' => 'Trial',
                                'class' => 'bg-yellow-100 text-yellow-700',
                            ],

                            'isolir' => [
                                'label' => 'Isolir',
                                'class' => 'bg-red-100 text-red-600',
                            ],

                            'dismantle' => [
                                'label' => 'Dismantle',
                                'class' => 'bg-gray-100 text-gray-500',
                            ],

                            'inactive' => [
                                'label' => 'Inactive',
                                'class' => 'bg-gray-100 text-gray-500',
                            ],
                        ];

                        $badge = $statusMap[$subStatus] ?? [
                            'label' => ucfirst($sub->status),
                            'class' => 'bg-gray-100 text-gray-500',
                        ];

                    @endphp

                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">

                        {{-- CUSTOMER --}}
                        <td class="px-5 py-4 text-sm text-gray-800">
                            {{ $sub->customer?->name ?? '-' }}
                        </td>

                        {{-- SERVICE --}}
                        <td class="px-5 py-4 text-sm text-gray-800">
                            {{ $sub->service?->name ?? '-' }}
                        </td>

                        {{-- PERIOD --}}
                        <td class="px-5 py-4 text-sm text-gray-600">

                            {{ \Carbon\Carbon::parse($sub->start_date)->format('j M Y') }}
                            –
                            {{ \Carbon\Carbon::parse($sub->end_date)->format('j M Y') }}

                        </td>

                        {{-- STATUS --}}
                        <td class="px-5 py-4">

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">

                                {{ $badge['label'] }}

                            </span>

                        </td>

                        {{-- ACTION --}}
                        <td class="px-5 py-4 relative overflow-visible">

                            @if ($subStatus === 'dismantle')
                                <span class="text-xs text-gray-300 select-none">
                                    —
                                </span>
                            @else
                                @include('function', [
                                    'resource' => 'subscriptions',
                                    'id' => $sub->id,
                                    'actions' => $availableActions,
                                ])
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-5 py-16 text-center text-sm text-gray-400">

                            Belum ada data subscription.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

@endsection
