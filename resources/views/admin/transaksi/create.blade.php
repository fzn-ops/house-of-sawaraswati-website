{{-- resources/views/admin/transaksi/create.blade.php --}}
<x-layouts.admin title="Buat Transaksi">

    <div class="mb-6">
        <a href="{{ route('admin.pesanan') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-rose-500 transition-colors">← Kembali ke Pesanan</a>
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100 mt-2">Buat Transaksi Baru</h1>
    </div>

    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-sm text-rose-600 dark:text-rose-400">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.transaksi.store') }}" id="transaksi-form">
            @csrf

            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-charcoal dark:text-gray-100">Produk <span class="text-rose-400">*</span></label>
                    <button type="button" onclick="addItem()"
                            class="text-xs text-rose-500 hover:text-rose-600 font-medium flex items-center gap-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Item
                    </button>
                </div>

                {{-- Header --}}
                <div class="flex items-center gap-3 mb-2 px-1">
                    <p class="flex-1 text-xs text-gray-400 dark:text-gray-500">Produk</p>
                    <p class="w-20 text-xs text-gray-400 dark:text-gray-500 text-center">Stok</p>
                    <p class="w-20 text-xs text-gray-400 dark:text-gray-500 text-center">Qty</p>
                    <p class="w-24 text-xs text-gray-400 dark:text-gray-500 text-right">Harga</p>
                    <div class="w-8"></div>
                </div>

                <div id="items-container" class="space-y-2">
                    {{-- Initial item row --}}
                </div>
            </div>

            {{-- Total Preview --}}
            <div class="bg-gray-50 dark:bg-[#252528] rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-charcoal dark:text-gray-100">Estimasi Total</span>
                    <span class="text-lg font-bold text-rose-500" id="total-preview">Rp0</span>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.pesanan') }}"
                   class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const products = @json($products);
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';
            row.id = `item-row-${itemIndex}`;

            let options = '<option value="">Pilih produk...</option>';
            products.forEach(p => {
                options += `<option value="${p.product_id}" data-price="${p.price}" data-stok="${p.stok}">${p.name} - Rp${Number(p.price).toLocaleString('id-ID')}</option>`;
            });

            row.innerHTML = `
                <div class="flex-1">
                    <select name="items[${itemIndex}][product_id]" required onchange="updateItemInfo(this, ${itemIndex})"
                            class="w-full appearance-none px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 bg-white dark:bg-[#1e1e21] cursor-pointer">
                        ${options}
                    </select>
                </div>
                <div class="w-20 text-center">
                    <span class="text-sm text-gray-400 dark:text-gray-500" id="stok-${itemIndex}">-</span>
                </div>
                <div class="w-20">
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required onchange="calcTotal()" oninput="calcTotal()"
                           class="w-full px-2 py-2.5 text-sm text-center bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                </div>
                <div class="w-24 text-right">
                    <span class="text-sm font-medium text-charcoal dark:text-gray-100" id="price-${itemIndex}">Rp0</span>
                </div>
                <button type="button" onclick="removeItem(${itemIndex})" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 dark:text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(row);
            itemIndex++;
        }

        function updateItemInfo(select, idx) {
            const opt = select.options[select.selectedIndex];
            const stokEl = document.getElementById(`stok-${idx}`);
            const priceEl = document.getElementById(`price-${idx}`);
            if (opt.value) {
                stokEl.textContent = opt.dataset.stok;
                priceEl.textContent = 'Rp' + Number(opt.dataset.price).toLocaleString('id-ID');
            } else {
                stokEl.textContent = '-';
                priceEl.textContent = 'Rp0';
            }
            calcTotal();
        }

        function removeItem(idx) {
            const row = document.getElementById(`item-row-${idx}`);
            if (row) row.remove();
            calcTotal();
        }

        function calcTotal() {
            let total = 0;
            document.querySelectorAll('#items-container > div').forEach(row => {
                const select = row.querySelector('select');
                const qtyInput = row.querySelector('input[type="number"]');
                if (select && select.value && qtyInput) {
                    const opt = select.options[select.selectedIndex];
                    total += Number(opt.dataset.price) * Number(qtyInput.value);
                }
            });
            document.getElementById('total-preview').textContent = 'Rp' + total.toLocaleString('id-ID');
        }

        // Add first item on load
        addItem();
    </script>
    @endpush

</x-layouts.admin>
