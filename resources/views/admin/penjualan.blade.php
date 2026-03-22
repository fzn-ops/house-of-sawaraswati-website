{{-- resources/views/admin/penjualan.blade.php --}}
<x-layouts.admin title="Penjualan">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal">Penjualan</h1>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-b border-gray-100">
            {{-- Filter --}}
            <div class="relative">
                <button onclick="toggleFilter()"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-600 hover:border-rose-400 hover:text-rose-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h18M7 10h10M11 16h2"/>
                    </svg>
                    Filter
                </button>
                <div id="filter-dropdown"
                     class="hidden absolute right-0 top-11 bg-white border border-gray-100 rounded-xl shadow-lg p-4 z-20 w-52">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Metode Pembayaran</p>
                    @foreach(['Semua','Transfer','Tunai / COD','QRIS','E-Wallet'] as $m)
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer group">
                        <input type="radio" name="filter-payment" value="{{ $m }}"
                               class="accent-rose-500" onchange="applyFilter()"
                               {{ $m === 'Semua' ? 'checked' : '' }}>
                        <span class="text-sm text-gray-600 group-hover:text-rose-500">{{ $m }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" id="search-input" placeholder="Cari Penjualan"
                       oninput="applyFilter()"
                       class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 placeholder-gray-400 w-52">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-rose-500 text-white text-sm">
                        <th class="w-10 px-4 py-3">
                            <input type="checkbox" id="check-all" onchange="toggleCheckAll(this)" class="accent-white rounded">
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">Order ID</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Produk</th>
                        <th class="px-4 py-3 text-left font-semibold">Total</th>
                        <th class="px-4 py-3 text-left font-semibold">Metode Pembayaran</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body"></tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between bg-rose-500 px-6 py-3">
            <p class="text-white text-sm" id="pagination-info">Menampilkan 0 dari 0 hasil</p>
            <div class="flex items-center gap-2">
                <button onclick="changePage(-1)" id="btn-prev"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div id="page-numbers" class="flex items-center gap-1"></div>
                <button onclick="changePage(1)" id="btn-next"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH/EDIT PENJUALAN ===== --}}
    <div id="tambah-modal"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeModalOutside(event)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modal-title" class="font-display text-xl font-semibold text-charcoal">Tambah Penjualan</h2>
                <button onclick="closeTambahModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">

                {{-- Produk List --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-medium text-gray-500">
                            Produk <span class="text-rose-400">*</span>
                        </label>
                        <button type="button" onclick="addProdukRow()"
                                class="text-xs text-rose-500 hover:text-rose-600 font-medium flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Produk
                        </button>
                    </div>
                    {{-- Header kolom --}}
                    <div class="flex items-center gap-2 mb-1.5 px-1">
                        <p class="flex-1 text-xs text-gray-400">Nama Produk</p>
                        <p class="w-20 text-xs text-gray-400">Ukuran</p>
                        <p class="w-14 text-xs text-gray-400 text-center">Qty</p>
                        <div class="w-8"></div>
                    </div>
                    <div id="produk-list" class="space-y-2"></div>
                </div>

                {{-- Total --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Total <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                        <input type="number" id="t-total" placeholder="0"
                               class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Metode Pembayaran <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <select id="t-metode"
                                class="w-full appearance-none px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 bg-white pr-8 cursor-pointer">
                            <option>Transfer</option>
                            <option>Tunai / COD</option>
                            <option>QRIS</option>
                            <option>E-Wallet</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal</label>
                    <input type="date" id="t-tanggal"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="closeTambahModal()"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button onclick="submitTambah()"
                        class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL DETAIL ===== --}}
    <div id="detail-modal"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeDetailModal()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-8" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-xl font-semibold text-charcoal">Detail Pesanan</h2>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="detail-content" class="space-y-3 text-sm"></div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/admin_penjualan.js') }}"></script>
    @endpush

</x-layouts.admin>