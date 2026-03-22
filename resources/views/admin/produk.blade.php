{{-- resources/views/admin/produk.blade.php --}}
<x-layouts.admin title="Produk">

    <div class="flex items-start justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal">Produk</h1>
    </div>

    {{-- Sub header + filter + tombol tambah --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            <p class="text-sm font-semibold text-charcoal mr-1">Produk</p>
            @php $tabs = ['Semua Produk','Hijab','Gamis','Aksesoris']; @endphp
            @foreach ($tabs as $i => $tab)
            <button onclick="filterTab(this,'{{ $tab }}')"
                    class="tab-btn flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                    {{ $i===0 ? 'bg-rose-500 text-white' : 'bg-white text-gray-500 border border-gray-200 hover:border-rose-300 hover:text-rose-500' }}">
                @if($i>0)<span class="w-2 h-2 rounded-full bg-current opacity-60"></span>@endif
                {{ $tab }}
            </button>
            @endforeach
        </div>
        <button onclick="openModal()"
                class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah Produk
        </button>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4" id="produk-grid">
        @php
            $dummies = [
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-coklat.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-motif-putih.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Hijab','image'=>'gamis-krem.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-coklat.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Aksesoris','image'=>'gamis-motif-putih.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-krem.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-coklat.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Hijab','image'=>'gamis-motif-putih.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-krem.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-coklat.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Gamis','image'=>'gamis-motif-putih.jpg'],
                ['name'=>'Alcy Set - Khmar','price'=>200000,'kategori'=>'Aksesoris','image'=>'gamis-krem.jpg'],
            ];
            $defaultVariants = json_encode([['ukuran'=>'Size 1','stok'=>10],['ukuran'=>'Size 2','stok'=>5]]);
        @endphp

        @foreach ($dummies as $p)
        <div class="produk-card bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-rose-200 transition-all duration-200"
             data-kategori="{{ $p['kategori'] }}"
             data-name="{{ $p['name'] }}"
             data-price="{{ $p['price'] }}"
             data-desc="Deskripsi produk contoh."
             data-panduan="Panjang depan: ±120cm"
             data-variants='{{ $defaultVariants }}'>
            <div class="bg-gray-50 overflow-hidden">
                <img src="{{ asset('images/products/'.$p['image']) }}"
                     alt="{{ $p['name'] }}"
                     class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-3">
                <p class="prod-name text-sm font-medium text-charcoal truncate">{{ $p['name'] }}</p>
                <p class="prod-price text-xs text-gray-500 mt-0.5 mb-2">Rp{{ number_format($p['price'],0,',','.') }}</p>
                <button onclick="openEditModal(this)"
                        class="w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all">
                    Edit Produk
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===================== MODAL ===================== --}}
    <div id="modal-overlay"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeModalOutside(event)">

        {{-- Close button --}}
        <button onclick="closeModal()"
                class="absolute top-5 right-5 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-rose-50 hover:text-rose-500 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto flex flex-col md:flex-row"
             onclick="event.stopPropagation()">

            {{-- Kiri: Form --}}
            <div class="flex-1 p-8 border-r border-gray-100">
                <h2 id="modal-title" class="font-display text-2xl font-semibold text-charcoal mb-6">Tambah Produk</h2>

                {{-- Upload Gambar --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Image</label>
                    <label for="upload-image"
                           class="flex flex-col items-center justify-center w-full h-36 border border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-rose-400 transition-colors bg-gray-50 overflow-hidden">
                        <input type="file" id="upload-image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <div id="upload-placeholder" class="flex flex-col items-center text-center pointer-events-none">
                            <svg class="w-8 h-8 text-rose-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-sm"><span class="text-rose-500 font-medium">Unggah</span> foto produk</span>
                        </div>
                        <img id="preview-img" src="" alt="" class="hidden w-full h-full object-cover">
                    </label>
                </div>

                {{-- Kategori + Harga --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Kategori</label>
                        <div class="relative">
                            <select id="input-kategori" onchange="updatePreview()"
                                    class="w-full appearance-none px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 bg-white pr-8 cursor-pointer">
                                <option>Hijab</option>
                                <option>Gamis</option>
                                <option>Aksesoris</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">
                            Harga <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                            <input type="number" id="input-harga" oninput="updatePreview()" placeholder="0"
                                   class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
                        </div>
                    </div>
                </div>

                {{-- Nama Produk --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                        Nama Produk <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="input-nama" oninput="updatePreview()" placeholder="cth. Alyara Set Khimar – Cream Beige"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
                </div>

                {{-- Varian Ukuran + Stok --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-medium text-gray-500">
                            Ukuran & Stok <span class="text-rose-400">*</span>
                        </label>
                        <button type="button" onclick="addVariant()"
                                class="text-xs text-rose-500 hover:text-rose-600 font-medium flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Ukuran
                        </button>
                    </div>
                    {{-- Header kolom --}}
                    <div class="flex items-center gap-2 mb-1.5 px-1">
                        <p class="flex-1 text-xs text-gray-400">Ukuran</p>
                        <p class="w-24 text-xs text-gray-400 text-center">Stok</p>
                        <div class="w-8"></div>
                    </div>
                    <div id="variant-list" class="space-y-2"></div>
                </div>

                {{-- Panduan Ukuran --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Panduan Ukuran</label>
                    <textarea id="input-panduan" oninput="updatePreview()" rows="3"
                              placeholder="cth. Panjang depan: ±120cm, Lingkar dada: ±140cm"
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 resize-none transition-colors"></textarea>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Deskripsi Produk</label>
                    <textarea id="input-deskripsi" oninput="updatePreview()" rows="4"
                              placeholder="Deskripsikan produk..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 resize-none transition-colors"></textarea>
                </div>
            </div>

            {{-- Kanan: Preview --}}
            <div class="w-full md:w-72 flex-shrink-0 p-8 flex flex-col bg-gray-50/50">
                <h2 class="font-display text-2xl font-semibold text-charcoal mb-5">Preview</h2>

                <div class="flex-1">
                    <div class="rounded-xl overflow-hidden bg-white mb-4 border border-gray-100 aspect-[3/4] flex items-center justify-center">
                        <img id="prev-image" src="" alt="" class="w-full h-full object-cover hidden">
                        <div id="prev-image-placeholder" class="text-gray-200">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama Produk</p>
                    <p class="text-base font-semibold text-charcoal mb-3" id="prev-nama">–</p>
                    <p class="text-xs text-gray-400 mb-0.5">Harga</p>
                    <p class="text-sm text-charcoal mb-3" id="prev-harga">–</p>
                    <p class="text-xs text-gray-400 mb-1.5">Ukuran</p>
                    <div class="flex flex-wrap gap-2 mb-3" id="prev-ukuran"></div>
                    <p class="text-xs text-gray-400 mb-1">Deskripsi Produk</p>
                    <p class="text-xs text-gray-600 leading-relaxed mb-3 line-clamp-4" id="prev-deskripsi">–</p>
                    <p class="text-xs text-gray-400 mb-1">Panduan Ukuran</p>
                    <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line" id="prev-panduan">–</p>
                </div>

                {{-- Tombol --}}
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button type="button" onclick="resetForm(true)"
                            class="py-2.5 border border-rose-400 text-rose-500 text-sm font-semibold rounded-xl hover:bg-rose-50 transition-colors">
                        Atur Ulang
                    </button>
                    <button type="button" id="btn-submit" onclick="submitProduk()"
                            class="py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                        Tambah Produk
                    </button>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/admin_produk.js') }}"></script>
    @endpush

</x-layouts.admin>