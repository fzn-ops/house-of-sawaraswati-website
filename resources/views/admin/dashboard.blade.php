{{-- resources/views/admin/dashboard.blade.php --}}
<x-layouts.admin title="Dashboard">

    <h1 class="text-2xl font-bold text-charcoal mb-6">Dashboard</h1>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-2xl font-bold text-charcoal">{{ $totalProduk }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Produk</p>
        </div>

        {{-- Transaksi Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal">{{ $transaksiHariIni }}</p>
            <p class="text-xs text-gray-400 mt-1">Transaksi Hari Ini</p>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Pendapatan Hari Ini</p>
        </div>

        {{-- Total User --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal">{{ $totalUser }}</p>
            <p class="text-xs text-gray-400 mt-1">Total User</p>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-charcoal">Transaksi Terbaru</h2>
            <a href="{{ route('admin.penjualan') }}" class="text-xs text-rose-500 hover:text-rose-600 font-medium transition-colors">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-50">
                        <th class="px-6 py-3 text-left font-medium">ID</th>
                        <th class="px-6 py-3 text-left font-medium">Tanggal</th>
                        <th class="px-6 py-3 text-left font-medium">Kasir</th>
                        <th class="px-6 py-3 text-left font-medium">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiTerbaru as $t)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3 text-sm text-charcoal font-medium">#{{ $t->transaction_id }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $t->user->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-charcoal">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>