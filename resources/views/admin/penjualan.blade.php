{{-- resources/views/admin/penjualan.blade.php --}}
<x-layouts.admin title="Penjualan">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100">Laporan Penjualan</h1>
    </div>

    {{-- ===== REPORTING: Date Range Filter ===== --}}
    <div class="flex flex-wrap items-center gap-3 mb-5">
        <div class="flex items-center bg-white dark:bg-[#1e1e21] border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <button onclick="setDateRange('today')"   class="date-range-btn px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-500 transition-colors">Hari Ini</button>
            <button onclick="setDateRange('7d')"       class="date-range-btn px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-500 transition-colors">7 Hari</button>
            <button onclick="setDateRange('30d')"      class="date-range-btn px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-500 transition-colors">30 Hari</button>
            <button onclick="setDateRange('month')"    class="date-range-btn px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-500 transition-colors">Bulan Ini</button>
            <button onclick="setDateRange('all')"      class="date-range-btn px-3 py-2 text-xs font-medium bg-rose-500 text-white transition-colors">Semua</button>
        </div>
        <div class="flex items-center gap-2">
            <input type="date" id="date-from"
                   onchange="setDateRange('custom')"
                   class="px-3 py-2 text-xs border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-[#1e1e21] dark:text-gray-100 focus:outline-none focus:border-rose-400 dark:focus:border-rose-500">
            <span class="text-xs text-gray-400 dark:text-gray-500">s/d</span>
            <input type="date" id="date-to"
                   onchange="setDateRange('custom')"
                   class="px-3 py-2 text-xs border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-[#1e1e21] dark:text-gray-100 focus:outline-none focus:border-rose-400 dark:focus:border-rose-500">
        </div>
        <button onclick="exportCSV()"
                class="ml-auto flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white text-xs font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </button>
    </div>

    {{-- ===== REPORTING: Stat Cards ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Pendapatan --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100" id="stat-revenue">Rp0</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total Pendapatan (Lunas)</p>
            <p class="text-xs text-amber-500 mt-0.5" id="stat-revenue-sub"></p>
        </div>
        {{-- Jumlah Transaksi --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100" id="stat-count">0</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Transaksi Lunas</p>
            <p class="text-xs text-amber-500 mt-0.5" id="stat-count-sub"></p>
        </div>
        {{-- Rata-rata --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100" id="stat-avg">Rp0</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Rata-rata / Transaksi</p>
        </div>
        {{-- Metode Terpopuler --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100" id="stat-top-method">-</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Metode Terpopuler</p>
        </div>
    </div>

    {{-- ===== REPORTING: Ringkasan per Metode ===== --}}
    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-6">
        <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-3">Ringkasan per Metode Pembayaran</p>
        <div class="grid grid-cols-3 gap-3" id="method-summary"></div>
    </div>

    {{-- ===== Table Card ===== --}}
    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            {{-- Filter --}}
            <div class="relative">
                <button onclick="toggleFilter()"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-600 dark:text-gray-300 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h18M7 10h10M11 16h2"/>
                    </svg>
                    Filter
                </button>
                <div id="filter-dropdown"
                     class="hidden absolute right-0 top-11 bg-white dark:bg-[#1e1e21] border border-gray-100 dark:border-gray-800 rounded-xl shadow-lg p-4 z-20 w-56">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">Metode Pembayaran</p>
                    @foreach(['Semua','Transfer','Tunai / COD','QRIS'] as $m)
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer group">
                        <input type="radio" name="filter-payment" value="{{ $m }}"
                               class="accent-rose-500" onchange="applyFilter()"
                               {{ $m === 'Semua' ? 'checked' : '' }}>
                        <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-rose-500">{{ $m }}</span>
                    </label>
                    @endforeach

                    <hr class="border-gray-100 dark:border-gray-700 my-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">Status Pembayaran</p>
                    @foreach(['Semua','paid','pending','failed'] as $s)
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer group">
                        <input type="radio" name="filter-status" value="{{ $s }}"
                               class="accent-rose-500" onchange="applyFilter()"
                               {{ $s === 'Semua' ? 'checked' : '' }}>
                        <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-rose-500">
                            {{ $s === 'Semua' ? 'Semua' : ($s === 'paid' ? 'Lunas' : ($s === 'pending' ? 'Pending' : 'Gagal')) }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" id="search-input" placeholder="Cari Penjualan"
                       oninput="applyFilter()"
                       class="pl-9 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-300 dark:focus:border-rose-500 placeholder-gray-400 dark:placeholder-gray-600 dark:bg-[#1e1e21] dark:text-gray-100 w-52">
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
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
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
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl shadow-xl w-full max-w-md p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modal-title" class="font-display text-xl font-semibold text-charcoal dark:text-gray-100">Tambah Penjualan</h2>
                <button onclick="closeTambahModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">

                {{-- Produk List --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400">
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
                        <p class="flex-1 text-xs text-gray-400 dark:text-gray-500">Nama Produk</p>
                        <p class="w-20 text-xs text-gray-400 dark:text-gray-500">Ukuran</p>
                        <p class="w-14 text-xs text-gray-400 dark:text-gray-500 text-center">Qty</p>
                        <div class="w-8"></div>
                    </div>
                    <div id="produk-list" class="space-y-2"></div>
                </div>

                {{-- Total --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        Total <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500">Rp</span>
                        <input type="number" id="t-total" placeholder="0"
                               class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 dark:text-gray-100 transition-colors">
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        Metode Pembayaran <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <select id="t-metode"
                                class="w-full appearance-none px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 dark:text-gray-100 pr-8 cursor-pointer">
                            <option>Transfer</option>
                            <option>Tunai / COD</option>
                            <option>QRIS</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 dark:text-gray-500 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tanggal</label>
                    <input type="date" id="t-tanggal"
                           class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 dark:text-gray-100 transition-colors">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="closeTambahModal()"
                        class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
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
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl shadow-xl w-full max-w-sm p-8" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-xl font-semibold text-charcoal dark:text-gray-100">Detail Pesanan</h2>
                <button onclick="closeDetailModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="detail-content" class="space-y-3 text-sm"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.REAL_DATA = @json($mappedData);
    </script>
    <script src="{{ asset('js/admin_penjualan.js') }}"></script>
    @endpush

</x-layouts.admin>
