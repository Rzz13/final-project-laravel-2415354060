{{--
    Shell template modal reusable.
    Judul dan isi form diinjeksi oleh JS dari <template id="erp-modal-tpl"> di tiap halaman.
    Tombol "Add Data" di halaman perlu punya atribut: data-open-modal data-title="..."
--}}
<div id="erp-modal" class="fixed inset-0 z-50 hidden items-center justify-center" role="dialog" aria-modal="true"
    aria-labelledby="erp-modal-title">

    {{-- Backdrop --}}
    <div id="erp-modal-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-[1px]"></div>

    {{-- Card --}}
    <div
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[520px] mx-4 px-8 py-8 max-h-[90vh] overflow-y-auto">

        {{-- Judul (diisi JS) --}}
        <h2 id="erp-modal-title" class="text-xl font-bold text-gray-900 text-center mb-6"></h2>

        {{-- Body: field form diinjeksi dari <template> oleh JS --}}
        <div id="erp-modal-body" class="space-y-5"></div>

        {{-- Footer Tombol --}}
        <div class="flex justify-end items-center gap-3 mt-7">
            <button id="erp-modal-cancel" type="button"
                class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200
                           rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="erp-modal-submit" type="button"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-gray-900 rounded-lg
                           hover:bg-gray-700 active:scale-95 transition-all">
                Submit
            </button>
        </div>

    </div>
</div>
