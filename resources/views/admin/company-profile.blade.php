{{-- resources/views/admin/company-profile.blade.php --}}
<x-layouts.admin title="Company Profile">

    <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100 mb-6">Profil Perusahaan</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-600 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Profil --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <h2 class="text-base font-semibold text-charcoal dark:text-gray-100 mb-4">Informasi Profil</h2>
            <form method="POST" action="{{ route('admin.company-profile.updateProfil') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tentang Kami</label>
                        <textarea name="about" rows="3"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors">{{ old('about', $profile->about ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Visi</label>
                        <textarea name="vision" rows="2"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors">{{ old('vision', $profile->vision ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Misi</label>
                        <textarea name="mission" rows="2"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors">{{ old('mission', $profile->mission ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Sejarah</label>
                        <textarea name="history" rows="3"
                                  class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none transition-colors">{{ old('history', $profile->history ?? '') }}</textarea>
                    </div>
                </div>
                <button type="submit"
                        class="mt-4 px-5 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                    Simpan Profil
                </button>
            </form>
        </div>

        {{-- Kontak --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <h2 class="text-base font-semibold text-charcoal dark:text-gray-100 mb-4">Informasi Kontak</h2>
            <form method="POST" action="{{ route('admin.company-profile.updateKontak') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}"
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $profile->email ?? '') }}"
                               class="w-full px-3 py-2.5 text-sm bg-gray-50 dark:bg-[#252528] border border-gray-300 dark:border-gray-600 shadow-inner rounded-xl focus:bg-white dark:focus:bg-[#1e1e21] focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition-colors">
                    </div>
                </div>
                <button type="submit"
                        class="mt-4 px-5 py-2.5 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
                    Simpan Kontak
                </button>
            </form>
        </div>

    </div>

</x-layouts.admin>
