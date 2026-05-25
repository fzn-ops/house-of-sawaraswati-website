{{-- resources/views/admin/discount-codes.blade.php --}}
<x-layouts.admin title="Kode Diskon">

    <div class="flex items-start justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100">Kode Diskon</h1>
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
        <p class="text-sm font-semibold text-charcoal dark:text-gray-100">Total: {{ $discountCodes->count() }} kode diskon</p>
        <button onclick="openDiscountModal()"
                class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah Kode Diskon
        </button>
    </div>

    {{-- Tabel Kode Diskon --}}
    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-rose-500 text-white text-sm">
                        <th class="text-left px-4 py-3 font-semibold">Kode</th>
                        <th class="text-left px-4 py-3 font-semibold">Tipe</th>
                        <th class="text-left px-4 py-3 font-semibold">Nilai</th>
                        <th class="text-left px-4 py-3 font-semibold">Label</th>
                        <th class="text-center px-4 py-3 font-semibold">Status</th>
                        <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discountCodes as $dc)
                    <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                        <td class="px-4 py-3 text-sm font-mono font-semibold text-charcoal dark:text-gray-100">{{ $dc->code }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            @if($dc->type === 'percent')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">Persen</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">Nominal</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ $dc->type === 'percent' ? $dc->value . '%' : 'Rp' . number_format($dc->value, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $dc->label ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.discount-codes.toggle', $dc) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors {{ $dc->is_active ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 hover:bg-gray-200' }}">
                                    {{ $dc->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $dc->id }}, '{{ $dc->code }}', '{{ $dc->type }}', {{ $dc->value }}, '{{ addslashes($dc->label ?? '') }}')"
                                        class="p-1.5 text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.discount-codes.destroy', $dc) }}" method="POST" class="inline" onsubmit="return confirmDeleteForm(this, { title: 'Hapus kode diskon?', text: 'Kode {{ $dc->code }} akan dihapus permanen.' })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Belum ada kode diskon.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div id="discount-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="closeDiscountModal()">
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl shadow-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
            <h2 id="modal-title" class="text-lg font-bold text-charcoal dark:text-gray-100 mb-5">Tambah Kode Diskon</h2>
            <form id="discount-form" method="POST" action="{{ route('admin.discount-codes.store') }}">
                @csrf
                <div id="method-field"></div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Kode Diskon</label>
                        <input type="text" name="code" id="input-code" required
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 uppercase placeholder-gray-400 dark:placeholder-gray-600"
                               placeholder="Contoh: DISKON100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tipe</label>
                            <select name="type" id="input-type"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-400 dark:focus:border-rose-500">
                                <option value="percent">Persen (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nilai</label>
                            <input type="number" name="value" id="input-value" required min="1"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-400 dark:focus:border-rose-500"
                                   placeholder="10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Label (opsional)</label>
                        <input type="text" name="label" id="input-label"
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#252528] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 placeholder-gray-400 dark:placeholder-gray-600"
                               placeholder="Contoh: Diskon 100% (GRATIS)">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeDiscountModal()"
                            class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function openDiscountModal() {
        document.getElementById('modal-title').textContent = 'Tambah Kode Diskon';
        document.getElementById('discount-form').action = '{{ route("admin.discount-codes.store") }}';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('input-code').value = '';
        document.getElementById('input-type').value = 'percent';
        document.getElementById('input-value').value = '';
        document.getElementById('input-label').value = '';

        const modal = document.getElementById('discount-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(id, code, type, value, label) {
        document.getElementById('modal-title').textContent = 'Edit Kode Diskon';
        document.getElementById('discount-form').action = '/admin/discount-codes/' + id;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('input-code').value = code;
        document.getElementById('input-type').value = type;
        document.getElementById('input-value').value = value;
        document.getElementById('input-label').value = label;

        const modal = document.getElementById('discount-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDiscountModal() {
        const modal = document.getElementById('discount-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    </script>
    @endpush

</x-layouts.admin>
