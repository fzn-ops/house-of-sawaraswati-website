{{-- resources/views/admin/produk.blade.php --}}
<x-layouts.admin title="Produk">

    <div class="flex items-start justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal">Produk</h1>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-600">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-600">
        {{ session('error') }}
    </div>
    @endif

    {{-- Sub header + tombol tambah --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <p class="text-sm font-semibold text-charcoal">Total: {{ $products->count() }} produk</p>
        <button onclick="openModal()"
                class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah Produk
        </button>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4" id="produk-grid">
        @forelse ($products as $p)
        <div class="produk-card bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-rose-200 transition-all duration-200"
             data-id="{{ $p->product_id }}"
             data-name="{{ $p->name }}"
             data-price="{{ $p->price }}"
             data-stok="{{ $p->stok }}"
             data-desc="{{ $p->description }}">
            <div class="bg-gray-50 overflow-hidden">
                @if($p->image)
                <img src="{{ asset('storage/' . $p->image) }}"
                     alt="{{ $p->name }}"
                     class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full aspect-square flex items-center justify-center bg-gray-100">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
            </div>
            <div class="p-3">
                <p class="prod-name text-sm font-medium text-charcoal truncate">{{ $p->name }}</p>
                <p class="prod-price text-xs text-gray-500 mt-0.5">Rp{{ number_format($p->price, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Stok: {{ $p->stok }}</p>
                <div class="flex gap-1 mt-2">
                    <button onclick="openEditModal({{ $p->product_id }})"
                            class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.produk.destroy', $p->product_id) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-red-400 hover:text-red-500 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400">
            <p class="text-sm">Belum ada produk. Klik "Tambah Produk" untuk menambahkan.</p>
        </div>
        @endforelse
    </div>

    {{-- ===================== MODAL TAMBAH ===================== --}}
    <div id="modal-overlay"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeModal()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl font-semibold text-charcoal">Tambah Produk</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Produk <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" required placeholder="cth. Alcy Set - Khimar"
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Harga <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                <input type="number" name="price" required placeholder="0"
                                       class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Stok <span class="text-rose-400">*</span></label>
                            <input type="number" name="stok" required placeholder="0" min="0"
                                   class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsikan produk..."
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Gambar Produk</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-rose-50 file:text-rose-500 hover:file:bg-rose-100 transition-colors">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                        Tambah Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== MODAL EDIT ===================== --}}
    <div id="edit-modal-overlay"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeEditModal()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl font-semibold text-charcoal">Edit Produk</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="edit-form" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Produk <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" id="edit-name" required
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Harga <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                <input type="number" name="price" id="edit-price" required
                                       class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Stok <span class="text-rose-400">*</span></label>
                            <input type="number" name="stok" id="edit-stok" required min="0"
                                   class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="edit-description" rows="3"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 shadow-inner rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Gambar Produk (kosongkan jika tidak ingin ganti)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-rose-50 file:text-rose-500 hover:file:bg-rose-100 transition-colors">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal() {
            document.getElementById('modal-overlay').classList.remove('hidden');
            document.getElementById('modal-overlay').classList.add('flex');
        }
        function closeModal() {
            document.getElementById('modal-overlay').classList.add('hidden');
            document.getElementById('modal-overlay').classList.remove('flex');
        }
        function openEditModal(id) {
            const card = document.querySelector(`[data-id="${id}"]`);
            if (!card) return;
            document.getElementById('edit-name').value = card.dataset.name;
            document.getElementById('edit-price').value = card.dataset.price;
            document.getElementById('edit-stok').value = card.dataset.stok;
            document.getElementById('edit-description').value = card.dataset.desc || '';
            document.getElementById('edit-form').action = `/admin/produk/${id}`;
            document.getElementById('edit-modal-overlay').classList.remove('hidden');
            document.getElementById('edit-modal-overlay').classList.add('flex');
        }
        function closeEditModal() {
            document.getElementById('edit-modal-overlay').classList.add('hidden');
            document.getElementById('edit-modal-overlay').classList.remove('flex');
        }
    </script>
    @endpush

</x-layouts.admin>