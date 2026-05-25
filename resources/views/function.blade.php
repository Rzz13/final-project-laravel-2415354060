<td class="px-5 py-4 relative">

    {{-- Tombol pemicu dropdown --}}
    <button type="button"
        class="action-trigger inline-flex items-center text-gray-400 hover:text-gray-700
                   hover:bg-gray-100 rounded-md p-1.5 transition-colors"
        data-resource="{{ $resource }}" data-id="{{ $id }}">
        <i class='bx bx-menu text-base'></i>
    </button>

    {{-- Panel dropdown --}}
    <div
        class="action-menu hidden absolute right-6 z-30 mt-1 w-44 bg-white border border-gray-200
                rounded-xl shadow-lg py-1 text-sm">

        @foreach ($actions as $item)
            @php $isDanger = $item['danger'] ?? false; @endphp

            <button type="button"
                class="dropdown-action flex items-center gap-2.5 w-full px-4 py-2 transition-colors
                           {{ $isDanger ? 'text-red-500 hover:bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}"
                data-resource="{{ $resource }}" data-action="{{ $item['action'] }}" data-id="{{ $id }}">

                <i class='bx {{ $item['icon'] }} text-base {{ $isDanger ? '' : 'text-gray-500' }}'></i>
                {{ $item['label'] }}
            </button>
        @endforeach

    </div>
</td>
