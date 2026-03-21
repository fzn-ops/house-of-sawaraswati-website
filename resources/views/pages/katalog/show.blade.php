{{-- resources/views/pages/katalog/show.blade.php --}}
<x-layouts.app title="{{ $product['name'] ?? 'Detail Produk' }} – House of Saraswati">

    <div class="max-w-5xl mx-auto px-6 lg:px-8 py-12">

        {{-- ===================== DETAIL PRODUK ===================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">

            {{-- Kiri: Gambar --}}
            <div class="rounded-2xl overflow-hidden bg-gray-50">
                <img
                    src="{{ asset('images/products/' . ($product['image'] ?? 'placeholder.jpg')) }}"
                    alt="{{ $product['name'] ?? 'Produk' }}"
                    class="w-full h-full object-cover object-top"
                >
            </div>

            {{-- Kanan: Info Produk --}}
            <div>
                {{-- Nama & Harga --}}
                <h1 class="font-display text-3xl font-semibold text-charcoal mb-1">
                    {{ $product['name'] ?? 'Alyara Set Khimar – Cream Beige' }}
                </h1>
                <p class="text-base text-gray-700 mb-6">
                    Rp{{ number_format($product['price'] ?? 300000, 0, ',', '.') }}
                </p>

                {{-- Ukuran --}}
                <div class="mb-6">
                    <h2 class="font-display text-lg font-semibold text-charcoal mb-3">Ukuran</h2>
                    <div class="flex flex-wrap gap-2">
                        @php $sizes = $product['sizes'] ?? ['Size 1', 'Size 2']; @endphp
                        @foreach ($sizes as $size)
                        <button
                            onclick="selectSize(this)"
                            class="size-btn px-4 py-1.5 text-sm border border-gray-300 rounded text-charcoal hover:border-rose-400 hover:text-rose-500 transition-colors">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <h2 class="font-display text-lg font-semibold text-charcoal mb-1">Status</h2>
                    <p class="text-sm {{ ($product['stok'] ?? true) ? 'text-gray-700' : 'text-red-500' }}">
                        {{ ($product['stok'] ?? true) ? 'Tersedia' : 'Habis' }}
                    </p>
                </div>

                {{-- Tombol Pesan --}}
                <a
                    href="https://wa.me/6281211882222?text=Halo, saya ingin memesan {{ urlencode($product['name'] ?? 'produk ini') }}"
                    target="_blank"
                    class="block w-full text-center py-3 bg-rose-500 text-white text-sm font-medium rounded hover:bg-rose-600 transition-colors mb-8">
                    Pesan via WhatsApp
                </a>

                {{-- Deskripsi Produk --}}
                <div class="mb-5">
                    <h2 class="font-display text-base font-semibold text-charcoal mb-2">Deskripsi Produk</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $product['description'] ?? 'Alyara Set Khimar hadir dengan siluet anggun dan desain yang lembut, memancarkan kesan elegan dan nyaman dalam setiap balutan. Didesain dengan potongan longgar yang nyaman serta detail renda halus pada bagian bawah khimar dan lengan, menciptakan tampilan feminin yang tetap sederhana dan berkelas. Cocok digunakan untuk kegiatan sehari-hari, kajian, hingga acara formal dengan tampilan yang tetap rapi dan menawan.' }}
                    </p>
                </div>

                {{-- Deskripsi Bahan --}}
                <div class="mb-5">
                    <h2 class="font-display text-base font-semibold text-charcoal mb-2">Deskripsi Bahan</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-2">
                        {{ $product['material'] ?? 'Menggunakan bahan Premium Soft Flow Fabric yang ringan, jatuh, dan tidak menerawang. Teksturnya halus di kulit serta memberikan efek drape yang anggun saat dikenakan.' }}
                    </p>
                    <p class="text-sm text-gray-600 font-medium mb-1">Karakteristik bahan:</p>
                    <ul class="text-sm text-gray-600 space-y-0.5">
                        @php
                            $karakteristik = $product['karakteristik'] ?? [
                                'Lembut dan nyaman dipakai seharian',
                                'Tidak panas dan breathable',
                                'Jatuh dengan siluet yang elegan',
                                'Tidak mudah kusut',
                                'Detail renda premium di bagian bawah',
                            ];
                        @endphp
                        @foreach ($karakteristik as $k)
                        <li class="flex items-start gap-1.5">
                            <span class="text-gray-500 mt-0.5">✓</span>
                            <span>{{ $k }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Panduan Ukuran --}}
                <div>
                    <h2 class="font-display text-base font-semibold text-charcoal mb-2">Panduan Ukuran</h2>
                    <p class="text-sm text-gray-500 mb-2">(All Size – Fit hingga XXL)</p>
                    <p class="text-sm font-medium text-charcoal mb-1">Atasan (Khimar):</p>
                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-0.5 mb-3">
                        <li>Panjang depan: ± 120 cm</li>
                        <li>Panjang belakang: ± 140 cm</li>
                        <li>Lingkar dada: ± 140 cm</li>
                    </ul>
                    <p class="text-sm font-medium text-charcoal mb-1">Gamis:</p>
                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-0.5">
                        <li>Panjang gamis: ± 135 cm</li>
                        <li>Lingkar dada: ± 120 cm</li>
                        <li>Lebar bawah: ± 180 cm</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ===================== PRODUK LAINNYA ===================== --}}
        <div class="mb-20">
            <h2 class="font-body text-base font-semibold text-charcoal mb-6">Produk Lainnya yang Mungkin Anda Suka</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                @php
                    $related = $relatedProducts ?? [
                        ['name' => 'Alyara Set Khmar - Ungu',      'price' => 300000, 'image' => 'gamis-coklat.jpg'],
                        ['name' => 'Aldera Set Khmar - Putih',     'price' => 300000, 'image' => 'gamis-motif-putih.jpg'],
                        ['name' => 'Alcy Set Khmar - Ungu',        'price' => 300000, 'image' => 'gamis-krem.jpg'],
                        ['name' => 'Alyara Set Khmar - Ungu Muda', 'price' => 300000, 'image' => 'gamis-coklat.jpg'],
                    ];
                @endphp
                @foreach ($related as $item)
                <a href="#" class="group block">
                    <div class="rounded-xl overflow-hidden bg-gray-50 mb-2">
                        <img
                            src="{{ asset('images/products/' . $item['image']) }}"
                            alt="{{ $item['name'] }}"
                            class="w-full aspect-[3/4] object-cover object-top group-hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                    <h3 class="text-sm text-charcoal group-hover:text-rose-500 transition-colors leading-snug">{{ $item['name'] }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/detail_produk.js') }}"></script>
    @endpush

</x-layouts.app>