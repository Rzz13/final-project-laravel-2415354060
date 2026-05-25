@extends('app')

@section('title', 'Services')
@section('page-title', 'Services')

@section('content')

    {{-- Tombol Add Data --}}
    <div class="flex justify-end mb-5">
        <button data-open-modal id="btn-add-service"
            class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold
                   px-4 py-2.5 rounded-lg hover:bg-gray-700 active:scale-95 transition-all">
            <i class='bx bx-plus text-base'></i>
            Add Data
        </button>
    </div>

    {{-- Template form Add Services --}}
    <template id="erp-modal-tpl" data-title="Add Services">
        <form data-endpoint="/api/services" data-method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Service Name</label>
                <input type="text" name="name" placeholder="Enter your name"
                    class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-700
                          placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Price</label>
                <input type="number" name="price" placeholder="Enter your price" min="0"
                    class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-700
                          placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Description</label>
                <textarea name="description" placeholder="Enter your description" rows="4"
                    class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-700
                             placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Status</label>
                <div class="relative">
                    <select name="status"
                        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500
                               focus:outline-none focus:ring-2 focus:ring-gray-300 appearance-none">
                        <option value="" disabled selected>Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <i
                        class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                </div>
            </div>
        </form>
    </template>

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Service
                        Name</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Price</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status
                    </th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 text-sm text-gray-800">{{ $service->name }}</td>
                        <td class="px-5 py-4 text-sm text-gray-800">
                            Rp{{ number_format($service->price, 0, ',', '.') }},00
                        </td>
                        <td class="px-5 py-4">
                            @if ($service->status)
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Inactive</span>
                            @endif
                        </td>

                        {{-- Tampilkan Deactivate jika active, Active jika inactive --}}
                        @include('function', [
                            'resource' => 'services',
                            'id' => $service->id,
                            'actions' => [
                                $service->status
                                    ? ['icon' => 'bx-power-off', 'label' => 'Deactivate', 'action' => 'deactivate']
                                    : ['icon' => 'bx-key', 'label' => 'Active', 'action' => 'activate'],
                                ['icon' => 'bx-edit-alt', 'label' => 'Edit', 'action' => 'edit'],
                                [
                                    'icon' => 'bx-trash',
                                    'label' => 'Delete',
                                    'action' => 'delete',
                                    'danger' => true,
                                ],
                            ],
                        ])
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-sm text-gray-400">
                            Belum ada data service.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
