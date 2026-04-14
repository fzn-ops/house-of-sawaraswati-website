{{-- resources/views/admin/users/index.blade.php --}}
<x-layouts.admin title="Manajemen User">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-charcoal">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}"
           class="px-5 py-2 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 transition-colors">
            Tambah User
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-600">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
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
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3 text-sm text-charcoal font-medium">{{ $user->id }}</td>
                        <td class="px-6 py-3 text-sm text-charcoal">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ ucfirst($user->role ?? 'kasir') }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-500 hover:border-red-400 hover:text-red-500 transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>
