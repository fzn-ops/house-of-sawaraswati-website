{{-- resources/views/admin/pesanan.blade.php --}}
<x-layouts.admin title="Pesanan">

    <div class="flex gap-6 items-start">

        {{-- ===== KIRI: Daftar Produk ===== --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-charcoal mb-5">Pesanan</h1>

            {{-- Search --}}
            <div class="relative mb-4">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" id="admin-search" placeholder="Cari produk"
                       class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-rose-300 placeholder-gray-400">
            </div>

            {{-- Filter Tabs --}}
            <div class="flex gap-2 mb-5 flex-wrap">
                @php $tabs = ['Semua Produk', 'Hijab', 'Gamis', 'Aksesoris']; @endphp
                @foreach ($tabs as $i => $tab)
                <button onclick="filterTab(this, '{{ $tab }}')"
                        class="tab-btn flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                        {{ $i === 0 ? 'bg-rose-500 text-white' : 'bg-white text-gray-500 border border-gray-200 hover:border-rose-300 hover:text-rose-500' }}">
                    @if ($i > 0)<span class="w-2 h-2 rounded-full bg-current opacity-60"></span>@endif
                    {{ $tab }}
                </button>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="admin-product-grid">
                @php
                    $products = [
                        ['id'=>1,  'name'=>'Aldera Set - Khmar',  'price'=>300000, 'kategori'=>'Gamis',     'image'=>'gamis-coklat.jpg',       'variants'=>[['ukuran'=>'Size 1','stok'=>10],['ukuran'=>'Size 2','stok'=>5]]],
                        ['id'=>2,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-motif-putih.jpg',   'variants'=>[['ukuran'=>'S','stok'=>8],['ukuran'=>'M','stok'=>12],['ukuran'=>'L','stok'=>3]]],
                        ['id'=>3,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-krem.jpg',          'variants'=>[['ukuran'=>'Size 1','stok'=>6],['ukuran'=>'Size 2','stok'=>4]]],
                        ['id'=>4,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Hijab',     'image'=>'gamis-coklat.jpg',        'variants'=>[['ukuran'=>'All Size','stok'=>20]]],
                        ['id'=>5,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-motif-putih.jpg',   'variants'=>[['ukuran'=>'S','stok'=>5],['ukuran'=>'M','stok'=>7]]],
                        ['id'=>6,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-krem.jpg',          'variants'=>[['ukuran'=>'Size 1','stok'=>9],['ukuran'=>'Size 2','stok'=>2]]],
                        ['id'=>7,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Aksesoris', 'image'=>'gamis-coklat.jpg',        'variants'=>[['ukuran'=>'Free Size','stok'=>15]]],
                        ['id'=>8,  'name'=>'Aldera Set - Coklat', 'price'=>250000, 'kategori'=>'Gamis',     'image'=>'gamis-motif-putih.jpg',   'variants'=>[['ukuran'=>'Size 1','stok'=>4],['ukuran'=>'Size 2','stok'=>6]]],
                        ['id'=>9,  'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-krem.jpg',          'variants'=>[['ukuran'=>'M','stok'=>11],['ukuran'=>'L','stok'=>3],['ukuran'=>'XL','stok'=>1]]],
                        ['id'=>10, 'name'=>'Aldera Set - Hitam',  'price'=>150000, 'kategori'=>'Hijab',     'image'=>'gamis-coklat.jpg',        'variants'=>[['ukuran'=>'All Size','stok'=>18]]],
                        ['id'=>11, 'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-motif-putih.jpg',   'variants'=>[['ukuran'=>'Size 1','stok'=>7],['ukuran'=>'Size 2','stok'=>5]]],
                        ['id'=>12, 'name'=>'Alcy Set - Khmar',    'price'=>200000, 'kategori'=>'Gamis',     'image'=>'gamis-krem.jpg',          'variants'=>[['ukuran'=>'S','stok'=>4],['ukuran'=>'M','stok'=>8]]],
                    ];
                @endphp

                @foreach ($products as $product)
                <div class="admin-product-card relative bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-rose-200 transition-all duration-200"
                     data-id="{{ $product['id'] }}"
                     data-name="{{ $product['name'] }}"
                     data-price="{{ $product['price'] }}"
                     data-kategori="{{ $product['kategori'] }}"
                     data-image="{{ asset('images/products/' . $product['image']) }}"
                     data-variants='{{ json_encode($product['variants']) }}'>

                    {{-- Gambar --}}
                    <div class="bg-gray-50 overflow-hidden">
                        <img src="{{ asset('images/products/' . $product['image']) }}"
                             alt="{{ $product['name'] }}"
                             class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-300">
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        <p class="text-sm font-medium text-charcoal truncate">{{ $product['name'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>

                        {{-- Tombol / Counter --}}
                        <div class="mt-2.5" id="action-{{ $product['id'] }}">
                            <button onclick="showSizePopup({{ $product['id'] }})"
                                    class="pilih-btn w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all duration-200">
                                Pilih Produk
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Size Popup (muncul di dalam card) --}}
                        <div id="size-popup-{{ $product['id'] }}"
                             class="hidden absolute inset-0 bg-white/95 backdrop-blur-sm rounded-2xl z-10 flex flex-col justify-center p-4">
                            <p class="text-xs font-semibold text-charcoal mb-2 truncate">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-400 mb-3">Pilih Ukuran:</p>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach ($product['variants'] as $v)
                                <button onclick="selectSize({{ $product['id'] }}, '{{ $v['ukuran'] }}', {{ $v['stok'] }})"
                                        class="size-option px-3 py-1 text-xs border border-gray-200 rounded-lg hover:border-rose-400 hover:text-rose-500 hover:bg-rose-50 transition-all
                                        {{ $v['stok'] == 0 ? 'opacity-40 cursor-not-allowed line-through' : '' }}"
                                        {{ $v['stok'] == 0 ? 'disabled' : '' }}
                                        data-ukuran="{{ $v['ukuran'] }}"
                                        data-stok="{{ $v['stok'] }}">
                                    {{ $v['ukuran'] }}
                                    <span class="text-gray-400 ml-0.5">({{ $v['stok'] }})</span>
                                </button>
                                @endforeach
                            </div>
                            <button onclick="hideSizePopup({{ $product['id'] }})"
                                    class="text-xs text-gray-400 hover:text-gray-600 transition-colors text-center">
                                Batal
                            </button>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== KANAN: Ringkasan Pesanan ===== --}}
        <div class="w-80 flex-shrink-0 bg-white rounded-2xl border border-gray-100 p-6 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto flex flex-col [&::-webkit-scrollbar]:hidden">
            <h2 class="text-xl font-bold text-charcoal mb-1">Ringkasan Pesanan</h2>

            <div class="mb-5">
                <p class="text-sm font-semibold text-charcoal mb-3" id="total-produk-label">Total Produk (0)</p>
                <div class="space-y-3 max-h-28 overflow-y-auto [&::-webkit-scrollbar]:hidden" id="order-items">
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada produk dipilih</p>
                </div>
            </div>

            <hr class="border-gray-100 mb-5">

            <div class="mb-6">
                <p class="text-sm font-semibold text-charcoal mb-3">Ringkasan Pembayaran</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span><span id="subtotal">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Pajak</span><span id="pajak">Rp0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Diskon</span><span id="diskon">Rp0</span>
                    </div>
                    <hr class="border-gray-100 my-2">
                    <div class="flex justify-between font-semibold text-charcoal">
                        <span>Total Pembayaran</span>
                        <span class="text-rose-500" id="total-bayar">Rp0</span>
                    </div>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="mb-5">
                <p class="text-sm font-semibold text-charcoal mb-3">Metode Pembayaran</p>
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="selectPayment(this, 'transfer')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 rounded-xl hover:border-rose-400 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">Transfer Bank</span>
                    </button>
                    <button onclick="selectPayment(this, 'cod')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 rounded-xl hover:border-rose-400 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">Tunai / COD</span>
                    </button>
                    <button onclick="selectPayment(this, 'qris')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 rounded-xl hover:border-rose-400 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">QRIS</span>
                    </button>
                    <button onclick="selectPayment(this, 'ewallet')"
                            class="payment-btn flex flex-col items-center gap-1.5 p-3 border border-gray-200 rounded-xl hover:border-rose-400 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">E-Wallet</span>
                    </button>
                </div>
                {{-- Label metode terpilih --}}
                <p class="text-xs text-rose-500 mt-2 hidden" id="selected-payment-label"></p>
            </div>

            <hr class="border-gray-100 mb-5">

            <button onclick="tambahkanPesanan()"
                    class="w-full py-3 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                Tambahkan Pesanan
            </button>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/admin_pesanan.js') }}"></script>
    @endpush

</x-layouts.admin>