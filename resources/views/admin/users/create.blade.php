{{-- resources/views/admin/users/create.blade.php --}}
<x-layouts.admin title="Tambah User">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-rose-500 transition-colors">← Kembali ke Daftar User</a>
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100 mt-2">Tambah User Baru</h1>
    </div>

    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-8 max-w-lg">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nama <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors @error('name') border-rose-400 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors @error('email') border-rose-400 @enderror">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Role <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <select name="role" required
                                class="w-full appearance-none px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 bg-white dark:bg-[#1e1e21] pr-8 cursor-pointer">
                            <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Password <span class="text-rose-400">*</span></label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors @error('password') border-rose-400 @enderror">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Konfirmasi Password <span class="text-rose-400">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 py-2.5 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                    Tambah User
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>
