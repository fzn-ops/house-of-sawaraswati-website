{{-- resources/views/admin/produk.blade.php --}}
<x-layouts.admin title="Produk">

    <div class="flex items-start justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100">Produk</h1>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-600 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-sm text-rose-600 dark:text-rose-400">
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-sm text-rose-600 dark:text-rose-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <p class="text-sm font-semibold text-charcoal dark:text-gray-100">Total: {{ $products->total() }} produk</p>
        <button onclick="openModal()"
                class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah Produk
        </button>
    </div>

    {{-- Search --}}
    <div class="relative mb-4">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" id="produk-search" placeholder="Cari produk"
               class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#1e1e21] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-300 dark:focus:border-rose-500 placeholder-gray-400 dark:placeholder-gray-600">
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-5 flex-wrap">
        @php $tabs = ['Semua Produk', 'Gamis Polos', 'Gamis Motif', 'Gamis Set', 'Kerudung']; @endphp
        @foreach ($tabs as $i => $tab)
        <button onclick="filterProdukTab(this, '{{ $tab }}')"
                class="produk-tab-btn flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                {{ $i === 0 ? 'bg-rose-500 text-white' : 'bg-white dark:bg-[#1e1e21] text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-rose-300 dark:hover:border-rose-500 hover:text-rose-500' }}">
            @if ($i > 0)<span class="w-2 h-2 rounded-full bg-current opacity-60"></span>@endif
            {{ $tab }}
        </button>
        @endforeach
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4" id="produk-grid">
        @forelse ($products as $p)
        <div class="produk-card bg-white dark:bg-[#1e1e21] rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 hover:border-rose-200 dark:hover:border-rose-700 transition-all duration-200"
             data-id="{{ $p->product_id }}"
             data-name="{{ $p->name }}"
             data-price="{{ $p->price }}"
             data-stok="{{ $p->stok }}"
             data-desc="{{ $p->description }}"
             data-category="{{ $p->category }}"
             data-sizes='@json($p->sizes->map(fn($s) => ["size" => $s->size, "stok" => $s->stok])->values())'>
            <div class="bg-gray-50 dark:bg-[#252528] overflow-hidden">
                @if($p->image)
                <img src="{{ asset('storage/' . $p->image) }}"
                     alt="{{ $p->name }}"
                     class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full aspect-square flex items-center justify-center bg-gray-100 dark:bg-[#252528]">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
            </div>
            <div class="p-3">
                <p class="prod-name text-sm font-medium text-charcoal dark:text-gray-100 truncate">{{ $p->name }}</p>
                <p class="prod-price text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rp{{ number_format($p->price, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Stok total: {{ $p->stok }}</p>
                @if($p->sizes->isNotEmpty())
                <div class="flex flex-wrap gap-1 mt-1">
                    @foreach($p->sizes as $s)
                    <span class="px-1.5 py-0.5 text-[10px] rounded-md bg-rose-50 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400">
                        {{ $s->size }}: {{ $s->stok }}
                    </span>
                    @endforeach
                </div>
                @endif
                <div class="flex gap-1 mt-2">
                    <button onclick="openEditModal({{ $p->product_id }})"
                            class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 transition-all">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.produk.destroy', $p->product_id) }}"
                          onsubmit="return confirmDeleteForm(this, {title: 'Hapus produk?', text: '{{ $p->name }} akan dihapus dari katalog.'})">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-red-400 dark:hover:border-red-500 hover:text-red-500 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400 dark:text-gray-500">
            <p class="text-sm">Belum ada produk. Klik "Tambah Produk" untuk menambahkan.</p>
        </div>
        @endforelse
    </div>

    @if ($products->hasPages())
    <div class="mt-6">
        {{ $products->links() }}
    </div>
    @endif

    {{-- ===================== MODAL TAMBAH ===================== --}}
    <div id="modal-overlay"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
         onclick="closeModal()">
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl font-semibold text-charcoal dark:text-gray-100">Tambah Produk</h2>
                <button onclick="closeModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nama Produk <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" required placeholder="cth. Alcy Set - Khimar"
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Harga <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500">Rp</span>
                            <input type="number" name="price" required placeholder="0"
                                   class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Size & Stok <span class="text-rose-400">*</span></label>
                            <button type="button" onclick="addSizeRow('add-size-list')"
                                    class="text-xs font-medium text-rose-500 hover:text-rose-600 transition-colors">
                                + Tambah Size
                            </button>
                        </div>
                        <div id="add-size-list" class="space-y-2"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsikan produk..."
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 resize-none transition-colors"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Kategori</label>
                        <select name="category"
                                class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:text-gray-100 transition-colors">
                            <option value="">Pilih Kategori</option>
                            <option value="Gamis Polos">Gamis Polos</option>
                            <option value="Gamis Motif">Gamis Motif</option>
                            <option value="Gamis Set">Gamis Set</option>
                            <option value="Kerudung">Kerudung</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Gambar Produk</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-rose-50 dark:file:bg-rose-900/20 file:text-rose-500 hover:file:bg-rose-100 transition-colors">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
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
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl shadow-xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl font-semibold text-charcoal dark:text-gray-100">Edit Produk</h2>
                <button onclick="closeEditModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="edit-form" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nama Produk <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" id="edit-name" required
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Harga <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500">Rp</span>
                            <input type="number" name="price" id="edit-price" required
                                   class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Size & Stok <span class="text-rose-400">*</span></label>
                            <button type="button" onclick="addSizeRow('edit-size-list')"
                                    class="text-xs font-medium text-rose-500 hover:text-rose-600 transition-colors">
                                + Tambah Size
                            </button>
                        </div>
                        <div id="edit-size-list" class="space-y-2"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="edit-description" rows="3"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 resize-none transition-colors"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Kategori</label>
                        <select name="category" id="edit-category"
                                class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:text-gray-100 transition-colors">
                            <option value="">Pilih Kategori</option>
                            <option value="Gamis Polos">Gamis Polos</option>
                            <option value="Gamis Motif">Gamis Motif</option>
                            <option value="Gamis Set">Gamis Set</option>
                            <option value="Kerudung">Kerudung</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Gambar Produk (kosongkan jika tidak ingin ganti)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-rose-50 dark:file:bg-rose-900/20 file:text-rose-500 hover:file:bg-rose-100 transition-colors">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
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
        const SIZE_OPTIONS = ['S', 'M', 'L', 'XL', 'XXL', 'All Size'];

        function reindexSizeList(listId) {
            const list = document.getElementById(listId);
            Array.from(list.children).forEach((row, idx) => {
                const sel = row.querySelector('select');
                const inp = row.querySelector('input[type="number"]');
                if (sel) sel.name = `sizes[${idx}][size]`;
                if (inp) inp.name = `sizes[${idx}][stok]`;
            });
        }

        function buildSizeRow(size = '', stok = 0) {
            const wrap = document.createElement('div');
            wrap.className = 'flex gap-2 items-center';

            const select = document.createElement('select');
            select.required = true;
            select.className = 'flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:text-gray-100 transition-colors';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih Size';
            select.appendChild(placeholder);

            SIZE_OPTIONS.forEach(opt => {
                const o = document.createElement('option');
                o.value = opt;
                o.textContent = opt;
                if (opt === size) o.selected = true;
                select.appendChild(o);
            });

            const stokInput = document.createElement('input');
            stokInput.type = 'number';
            stokInput.required = true;
            stokInput.min = '0';
            stokInput.placeholder = 'Stok';
            stokInput.value = stok;
            stokInput.className = 'w-24 px-3 py-2 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 placeholder-gray-300 dark:placeholder-gray-600 transition-colors';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'p-2 text-gray-400 hover:text-rose-500 transition-colors';
            removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
            removeBtn.onclick = () => {
                const list = wrap.parentElement;
                wrap.remove();
                if (list) {
                    if (list.children.length === 0) {
                        list.appendChild(buildSizeRow());
                    }
                    reindexSizeList(list.id);
                }
            };

            wrap.appendChild(select);
            wrap.appendChild(stokInput);
            wrap.appendChild(removeBtn);
            return wrap;
        }

        function addSizeRow(listId) {
            const list = document.getElementById(listId);
            list.appendChild(buildSizeRow());
            reindexSizeList(listId);
        }

        function resetSizeList(listId, sizes) {
            const list = document.getElementById(listId);
            list.innerHTML = '';
            const rows = (sizes && sizes.length) ? sizes : [{ size: '', stok: 0 }];
            rows.forEach(r => list.appendChild(buildSizeRow(r.size, r.stok)));
            reindexSizeList(listId);
        }

        function openModal() {
            resetSizeList('add-size-list', []);
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
            document.getElementById('edit-description').value = card.dataset.desc || '';
            document.getElementById('edit-category').value = card.dataset.category || '';

            let sizes = [];
            try { sizes = JSON.parse(card.dataset.sizes || '[]'); } catch (e) { sizes = []; }
            resetSizeList('edit-size-list', sizes);

            document.getElementById('edit-form').action = `/admin/produk/${id}`;
            document.getElementById('edit-modal-overlay').classList.remove('hidden');
            document.getElementById('edit-modal-overlay').classList.add('flex');
        }
        function closeEditModal() {
            document.getElementById('edit-modal-overlay').classList.add('hidden');
            document.getElementById('edit-modal-overlay').classList.remove('flex');
        }

        let activeProdukTab = 'Semua Produk';

        document.getElementById('produk-search')?.addEventListener('input', function () {
            applyProdukFilter();
        });

        function filterProdukTab(btn, tab) {
            document.querySelectorAll('.produk-tab-btn').forEach(b => {
                b.classList.remove('bg-rose-500', 'text-white');
                b.classList.add('bg-white', 'dark:bg-[#1e1e21]', 'text-gray-500', 'dark:text-gray-400', 'border', 'border-gray-200', 'dark:border-gray-700');
            });
            btn.classList.add('bg-rose-500', 'text-white');
            btn.classList.remove('bg-white', 'dark:bg-[#1e1e21]', 'text-gray-500', 'dark:text-gray-400', 'border', 'border-gray-200', 'dark:border-gray-700');

            activeProdukTab = tab;
            applyProdukFilter();
        }

        function applyProdukFilter() {
            const q = (document.getElementById('produk-search')?.value || '').toLowerCase();

            document.querySelectorAll('.produk-card').forEach(card => {
                const name = (card.dataset.name || '').toLowerCase();
                const category = card.dataset.category || '';

                const matchSearch = !q || name.includes(q);
                const matchTab = activeProdukTab === 'Semua Produk' || category === activeProdukTab;

                card.classList.toggle('hidden', !(matchSearch && matchTab));
            });
        }
    </script>
    @endpush

</x-layouts.admin>
