<x-layouts.app title="Katalog – House of Saraswati">

    {{-- ===================== HERO BANNER ===================== --}}
    <section class="relative w-full h-70 overflow-hidden">
        {{-- Background image gelap --}}
        <img
            src="{{ asset('images/katalog_image.png') }}"
            alt="Katalog"
            class="absolute inset-0 w-full h-full object-cover object-center"
        >
        {{-- Overlay gelap --}}
        <div class="absolute inset-0 bg-black/45"></div>

        {{-- Judul tengah --}}
        <div class="relative z-10 h-full flex items-center justify-center">
            <h1 class="font-display text-5xl font-light text-white tracking-wide">Katalog</h1>
        </div>
    </section>

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <section class="max-w-6xl mx-auto px-6 lg:px-8 py-10">
        <div class="flex gap-8 items-start">

            {{-- ===== SIDEBAR FILTER ===== --}}
            <aside class="w-48 flex-shrink-0">

                {{-- Search --}}
                <div class="relative mb-6">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input
                        type="text"
                        placeholder="Pencarian"
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-rose-300 placeholder-gray-400"
                        id="search-input"
                    >
                </div>

                {{-- Filter: Kategori --}}
                <div class="mb-5" id="filter-kategori">
                    <button class="flex items-center justify-between w-full mb-3 group" onclick="toggleFilter('kategori')">
                        <span class="text-sm font-semibold text-charcoal">Kategori</span>
                        <svg id="icon-kategori" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    <div id="list-kategori" class="space-y-2">
                        @php
                            $kategori = ['Gamis Polos', 'Gamis Motif', 'Gamis Set'];
                        @endphp
                        @foreach ($kategori as $k)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="kategori" value="{{ $k }}"
                                   class="w-3.5 h-3.5 accent-rose-500 cursor-pointer"
                                   onchange="filterProducts()">
                            <span class="text-sm text-gray-600 group-hover:text-rose-500 transition-colors">{{ $k }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-100 mb-5">

                {{-- Filter: Harga --}}
                <div class="mb-5">
                    <button class="flex items-center justify-between w-full mb-3" onclick="toggleFilter('harga')">
                        <span class="text-sm font-semibold text-charcoal">Harga</span>
                        <svg id="icon-harga" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    <div id="list-harga" class="space-y-2">
                        @php
                            $harga = ['Di bawah Rp300.000', 'Rp300.000 – Rp500.000', 'Di atas Rp500.000'];
                        @endphp
                        @foreach ($harga as $h)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="harga" value="{{ $h }}"
                                   class="w-3.5 h-3.5 accent-rose-500 cursor-pointer"
                                   onchange="filterProducts()">
                            <span class="text-sm text-gray-600 group-hover:text-rose-500 transition-colors">{{ $h }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-100 mb-5">

                {{-- Filter: Ketersediaan --}}
                <div class="mb-5">
                    <button class="flex items-center justify-between w-full mb-3" onclick="toggleFilter('stok')">
                        <span class="text-sm font-semibold text-charcoal">Ketersediaan</span>
                        <svg id="icon-stok" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    <div id="list-stok" class="space-y-2">
                        @php
                            $stok = ['Semua', 'Tersedia', 'Habis'];
                        @endphp
                        @foreach ($stok as $s)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="stok" value="{{ $s }}"
                                   class="w-3.5 h-3.5 accent-rose-500 cursor-pointer"
                                   onchange="filterProducts()">
                            <span class="text-sm text-gray-600 group-hover:text-rose-500 transition-colors">{{ $s }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </aside>

            {{-- ===== AREA PRODUK ===== --}}
            <div class="flex-1 ">

                {{-- Sort bar --}}
                <div class="flex justify-end mb-6">
                    <div class="relative">
                        <select id="sort-select" onchange="filterProducts()"
                                class="appearance-none text-sm border border-gray-200 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:border-rose-300 text-gray-600 bg-white cursor-pointer">
                            <option value="terbaru">terbaru</option>
                            <option value="harga-asc">harga terendah</option>
                            <option value="harga-desc">harga tertinggi</option>
                            <option value="nama">nama A–Z</option>
                        </select>
                        <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Grid Produk --}}
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-45 overflow-y-auto max-h-[calc(100vh-12rem)] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" id="product-grid">
                    @php
                        // Data dummy — ganti dengan $products dari controller
                        $products = [
                            ['name' => 'Gamis Set - Coklat',  'price' => 300000, 'kategori' => 'Gamis Set',   'stok' => true,  'image' => 'gamis-coklat.jpg'],
                            ['name' => 'Gamis Motif - Putih', 'price' => 300000, 'kategori' => 'Gamis Motif', 'stok' => true,  'image' => 'gamis-motif-putih.jpg'],
                            ['name' => 'Gamis Set - Krem',    'price' => 300000, 'kategori' => 'Gamis Set',   'stok' => true,  'image' => 'gamis-krem.jpg'],
                            ['name' => 'Gamis Set - Coklat',  'price' => 300000, 'kategori' => 'Gamis Set',   'stok' => false, 'image' => 'gamis-coklat.jpg'],
                            ['name' => 'Gamis Motif - Putih', 'price' => 300000, 'kategori' => 'Gamis Motif', 'stok' => true,  'image' => 'gamis-motif-putih.jpg'],
                            ['name' => 'Gamis Set - Krem',    'price' => 300000, 'kategori' => 'Gamis Set',   'stok' => true,  'image' => 'gamis-krem.jpg'],
                        ];
                    @endphp

                    @foreach ($products as $i => $product)
                    <div class="product-item group cursor-pointer" data-index="{{ $i }}">
                        {{-- Gambar --}}
                        <div class="relative overflow-hidden rounded-xl bg-gray-50 mb-3">
                            <img
                                src="{{ asset('images/products/' . $product['image']) }}"
                                alt="{{ $product['name'] }}"
                                class="w-full aspect-[3/4] object-cover object-top group-hover:scale-105 transition-transform duration-500"
                            >
                            {{-- Badge Habis --}}
                            @if (!$product['stok'])
                            <div class="absolute top-3 left-3 bg-gray-800/70 text-white text-xs px-2 py-1 rounded-md">
                                Habis
                            </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div>
                            <h3 class="text-sm font-medium text-charcoal group-hover:text-rose-500 transition-colors">{{ $product['name'] }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Empty state --}}
                <div id="empty-state" class="hidden text-center py-20 text-gray-400">
                    <p class="text-sm">Tidak ada produk yang ditemukan.</p>
                </div>

            </div>
        </div>
    </section>

@push('scripts')
    <script src="{{ asset('js/katalog.js') }}"></script>
@endpush

</x-layouts.app>