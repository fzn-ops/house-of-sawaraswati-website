{{-- resources/views/admin/pesanan.blade.php --}}
<x-layouts.admin title="Pesanan">

    <div class="flex gap-6 items-start">

        {{-- ===== KIRI: Daftar Produk ===== --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100 mb-5">Pesanan</h1>

            {{-- Search --}}
            <div class="relative mb-4">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" id="admin-search" placeholder="Cari produk"
                       class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#1e1e21] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-300 dark:focus:border-rose-500 placeholder-gray-400 dark:placeholder-gray-600">
            </div>

            {{-- Filter Tabs --}}
            <div class="flex gap-2 mb-5 flex-wrap">
                @php $tabs = ['Semua Produk', 'Gamis Polos', 'Gamis Motif', 'Gamis Set', 'Kerudung']; @endphp
                @foreach ($tabs as $i => $tab)
                <button onclick="filterTab(this, '{{ $tab }}')"
                        class="tab-btn flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                        {{ $i === 0 ? 'bg-rose-500 text-white' : 'bg-white dark:bg-[#1e1e21] text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-rose-300 dark:hover:border-rose-500 hover:text-rose-500' }}">
                    @if ($i > 0)<span class="w-2 h-2 rounded-full bg-current opacity-60"></span>@endif
                    {{ $tab }}
                </button>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="admin-product-grid">
                @foreach ($products as $product)
                @php
                    $variants = $product->sizes->map(fn($s) => ['ukuran' => $s->size, 'stok' => $s->stok])->values()->all();
                    if (empty($variants)) {
                        $variants = [['ukuran' => 'All Size', 'stok' => $product->stok]];
                    }
                    $imagePath = $product->image ? asset('storage/' . $product->image) : asset('images/hero_image.png');
                @endphp
                <div class="admin-product-card relative bg-white dark:bg-[#1e1e21] rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 hover:border-rose-200 transition-all duration-200"
                     data-id="{{ $product->product_id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-kategori="{{ $product->category ?? '' }}"
                     data-image="{{ $imagePath }}"
                     data-variants='{{ json_encode($variants) }}'>

                    {{-- Gambar --}}
                    <div class="bg-gray-50 dark:bg-[#252528] overflow-hidden flex items-center justify-center p-0 aspect-square">
                        <img src="{{ $imagePath }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        <p class="text-sm font-medium text-charcoal dark:text-gray-100 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                        {{-- Tombol / Counter --}}
                        <div class="mt-2.5" id="action-{{ $product->product_id }}">
                            <button onclick="showSizePopup({{ $product->product_id }})"
                                    class="pilih-btn w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 transition-all duration-200">
                                Pilih Produk
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Size Popup (muncul di dalam card) --}}
                        <div id="size-popup-{{ $product->product_id }}"
                             class="hidden absolute inset-0 bg-white/95 dark:bg-[#1e1e21]/95 backdrop-blur-sm rounded-2xl z-10 flex flex-col justify-center p-4">
                            <p class="text-xs font-semibold text-charcoal dark:text-gray-100 mb-2 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Pilih Ukuran:</p>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach ($variants as $v)
                                <button onclick="selectSize({{ $product->product_id }}, '{{ $v['ukuran'] }}', {{ $v['stok'] }})"
                                        class="size-option px-3 py-1 text-xs border border-gray-200 dark:border-gray-700 rounded-lg hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 hover:bg-rose-50 transition-all
                                        {{ $v['stok'] == 0 ? 'opacity-40 cursor-not-allowed line-through' : '' }}"
                                        {{ $v['stok'] == 0 ? 'disabled' : '' }}
                                        data-ukuran="{{ $v['ukuran'] }}"
                                        data-stok="{{ $v['stok'] }}">
                                    {{ $v['ukuran'] }}
                                    <span class="text-gray-400 dark:text-gray-500 ml-0.5">({{ $v['stok'] }})</span>
                                </button>
                                @endforeach
                            </div>
                            <button onclick="hideSizePopup({{ $product->product_id }})"
                                    class="text-xs text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-center">
                                Batal
                            </button>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== KANAN: Ringkasan Pesanan ===== --}}
        <div class="w-80 flex-shrink-0 bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-6 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto flex flex-col [&::-webkit-scrollbar]:hidden">
            <h2 class="text-xl font-bold text-charcoal dark:text-gray-100 mb-1">Ringkasan Pesanan</h2>

            <div class="mb-5">
                <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-3" id="total-produk-label">Total Produk (0)</p>
                <div class="space-y-3 max-h-28 overflow-y-auto [&::-webkit-scrollbar]:hidden" id="order-items">
                    <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Belum ada produk dipilih</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800 mb-5">

            {{-- Kode Diskon --}}
            <div class="mb-5">
                <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-2">Kode Diskon</p>
                <div class="flex gap-2">
                    <input type="text" id="discount-code-input" placeholder="Masukkan kode diskon"
                           class="flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 focus:bg-white dark:focus:bg-[#1e1e21] transition-colors uppercase placeholder-gray-400 dark:placeholder-gray-600"
                           autocomplete="off">
                    <button type="button" onclick="applyDiscountCode()"
                            class="px-4 py-2 bg-rose-500 text-white text-xs font-semibold rounded-xl hover:bg-rose-600 transition-colors flex-shrink-0">
                        Terapkan
                    </button>
                </div>
                <p id="discount-feedback" class="text-xs mt-1.5 hidden"></p>
            </div>

            <div class="mb-6">
                <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-3">Ringkasan Pembayaran</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Subtotal</span><span id="subtotal">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Pajak</span><span id="pajak">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span id="diskon-label">Diskon</span><span id="diskon">Rp0</span>
                    </div>
                    <hr class="border-gray-100 dark:border-gray-800 my-2">
                    <div class="flex justify-between font-semibold text-charcoal dark:text-gray-100">
                        <span>Total Pembayaran</span>
                        <span class="text-rose-500" id="total-bayar">Rp0</span>
                    </div>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="mb-5">
                <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-3">Metode Pembayaran</p>
                <div class="grid grid-cols-3 gap-2">
                    <button onclick="selectPayment(this, 'transfer')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-rose-400 dark:hover:border-rose-500 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Transfer Bank</span>
                    </button>
                    <button onclick="selectPayment(this, 'cod')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-rose-400 dark:hover:border-rose-500 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Tunai / COD</span>
                    </button>
                    <button onclick="selectPayment(this, 'qris')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-rose-400 dark:hover:border-rose-500 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">QRIS</span>
                    </button>
                </div>
                {{-- Label metode terpilih --}}
                <p class="text-xs text-rose-500 mt-2 hidden" id="selected-payment-label"></p>
            </div>

            {{-- Input Uang Tunai (hanya muncul saat metode COD/Tunai) --}}
            <div id="cash-input-section" class="mb-5 hidden">
                <hr class="border-gray-100 dark:border-gray-800 mb-5">
                <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-2">Uang Diterima</p>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500">Rp</span>
                    <input type="text" id="cash-amount-input" placeholder="0"
                           oninput="formatCashInput(this)"
                           class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 focus:bg-white dark:focus:bg-[#1e1e21] transition-colors placeholder-gray-400 dark:placeholder-gray-600"
                           inputmode="numeric" autocomplete="off">
                </div>

                {{-- Kembalian --}}
                <div id="kembalian-display" class="mt-3 bg-gray-50 dark:bg-[#252528] rounded-xl p-3 hidden">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-charcoal dark:text-gray-100">Kembalian</span>
                        <span class="text-sm font-bold text-green-600" id="kembalian-value">Rp0</span>
                    </div>
                    <p id="kembalian-warning" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800 mb-5">

            <button id="btn-tambah-pesanan" onclick="tambahkanPesanan()"
                    class="w-full py-3 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                Tambahkan Pesanan
            </button>
        </div>
    </div>

    {{-- ===== MODAL STRUK ===== --}}
    <div id="receipt-modal"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeReceipt()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-0 overflow-hidden" onclick="event.stopPropagation()">
            <div id="receipt-content" class="p-6">
                <div class="text-center mb-4">
                    <p class="text-base font-bold text-charcoal">House of Saraswati</p>
                    <p class="text-xs text-gray-500">Hijab & Gamis Collection</p>
                    <p class="text-xs text-gray-400 mt-1" id="receipt-date"></p>
                </div>
                <hr class="border-dashed border-gray-300 mb-3">
                <p class="text-xs text-gray-500 mb-2">Order ID: <span class="font-medium text-charcoal" id="receipt-order-id"></span></p>
                <p class="text-xs text-gray-500 mb-3">Kasir: <span class="font-medium text-charcoal" id="receipt-kasir"></span></p>
                <hr class="border-dashed border-gray-300 mb-3">
                <div id="receipt-items" class="space-y-1.5 mb-3"></div>
                <hr class="border-dashed border-gray-300 mb-3">
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span><span id="receipt-subtotal"></span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Pajak (1%)</span><span id="receipt-pajak"></span>
                    </div>
                    <div class="flex justify-between text-gray-500" id="receipt-diskon-row">
                        <span>Diskon</span><span id="receipt-diskon"></span>
                    </div>
                    <hr class="border-gray-200 my-1">
                    <div class="flex justify-between font-bold text-sm text-charcoal">
                        <span>Total</span><span id="receipt-total"></span>
                    </div>
                    <div class="flex justify-between text-gray-500" id="receipt-bayar-row">
                        <span>Bayar</span><span id="receipt-bayar"></span>
                    </div>
                    <div class="flex justify-between text-gray-500" id="receipt-kembalian-row">
                        <span>Kembalian</span><span id="receipt-kembalian"></span>
                    </div>
                </div>
                <hr class="border-dashed border-gray-300 mt-3 mb-3">
                <div class="text-center">
                    <p class="text-xs text-gray-500">Metode: <span class="font-medium" id="receipt-metode"></span></p>
                    <p class="text-xs text-gray-400 mt-2">Terima kasih atas pembelian Anda!</p>
                </div>
            </div>
            <div class="flex border-t border-gray-100">
                <button onclick="printReceipt()"
                        class="flex-1 py-3 text-sm font-semibold text-rose-500 hover:bg-rose-50 transition-colors">
                    Cetak Struk
                </button>
                <button onclick="closeReceipt()"
                        class="flex-1 py-3 text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors border-l border-gray-100">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/admin_pesanan.js') }}"></script>
    @endpush

</x-layouts.admin>