{{-- resources/views/admin/users/index.blade.php --}}
<x-layouts.admin title="Manajemen User">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}"
           class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah User
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-600 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-rose-500 text-white text-sm">
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Nama</th>
                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                        <th class="px-6 py-3 text-left font-semibold">Role</th>
                        <th class="px-6 py-3 text-left font-semibold">Dibuat</th>
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-3 text-sm text-charcoal dark:text-gray-100 font-medium">{{ $user->id }}</td>
                        <td class="px-6 py-3 text-sm text-charcoal dark:text-gray-100">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' }}">
                                {{ ucfirst($user->role ?? 'kasir') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-red-400 dark:hover:border-red-500 hover:text-red-500 transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Belum ada user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>
