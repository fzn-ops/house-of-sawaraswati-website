{{-- resources/views/admin/dashboard.blade.php --}}
<x-layouts.admin title="Dashboard">

    <h1 class="text-2xl font-bold text-charcoal dark:text-gray-100 mb-6">Dashboard</h1>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Produk --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-500 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-2xl font-bold text-charcoal dark:text-gray-100">{{ $totalProduk }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total Produk</p>
        </div>

        {{-- Transaksi Hari Ini --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal dark:text-gray-100">{{ $transaksiHariIni }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Transaksi Hari Ini</p>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal dark:text-gray-100">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pendapatan Hari Ini</p>
        </div>

        {{-- Total User --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-charcoal dark:text-gray-100">{{ $totalUser }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total User</p>
        </div>
    </div>

    {{-- Statistik Penjualan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Pendapatan --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total Pendapatan (Lunas)</p>
            @if($pendingCount > 0)
            <p class="text-xs text-amber-500 mt-0.5">{{ $pendingCount }} transaksi pending</p>
            @endif
        </div>
        {{-- Jumlah Transaksi --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100">{{ number_format($totalPaidCount, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Transaksi Lunas</p>
            @if($pendingCount > 0 || $failedCount > 0)
            <p class="text-xs text-amber-500 mt-0.5">
                + {{ $pendingCount > 0 ? $pendingCount . ' pending' : '' }}{{ $pendingCount > 0 && $failedCount > 0 ? ', ' : '' }}{{ $failedCount > 0 ? $failedCount . ' gagal' : '' }}
            </p>
            @endif
        </div>
        {{-- Rata-rata --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100">Rp{{ number_format($avgPerTransaction, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Rata-rata / Transaksi</p>
        </div>
        {{-- Metode Terpopuler --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-charcoal dark:text-gray-100">{{ $topMethodLabel }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Metode Terpopuler</p>
        </div>
    </div>

    {{-- Grid: Pie Chart + Transaksi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Ringkasan per Metode Pembayaran --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <p class="text-sm font-semibold text-charcoal dark:text-gray-100 mb-5">Ringkasan per Metode Pembayaran</p>
            @php
                $methods = ['transfer' => 'Transfer', 'cod' => 'Tunai / COD', 'qris' => 'QRIS'];
                $chartData = [];
                $chartLabels = [];
                $chartColors = ['#3b82f6', '#22c55e', '#a855f7'];
                $methodStats = [];
                foreach ($methods as $key => $label) {
                    $methodData = $revenueByMethod->get($key);
                    $rev = $methodData ? $methodData->total : 0;
                    $cnt = $methodData ? $methodData->cnt : 0;
                    $percent = $totalRevenue > 0 ? round(($rev / $totalRevenue) * 100) : 0;
                    $chartLabels[] = $label;
                    $chartData[] = $rev;
                    $methodStats[] = ['label' => $label, 'rev' => $rev, 'cnt' => $cnt, 'percent' => $percent];
                }
            @endphp
            <div class="flex items-center gap-6">
                <div class="w-36 h-36 flex-shrink-0">
                    <canvas id="paymentPieChart"></canvas>
                </div>
                <div class="flex-1 space-y-4">
                    @foreach($methodStats as $i => $stat)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background: {{ $chartColors[$i] }}"></div>
                                <span class="text-sm font-medium text-charcoal dark:text-gray-100">{{ $stat['label'] }}</span>
                            </div>
                            <span class="text-sm font-bold text-charcoal dark:text-gray-100">{{ $stat['percent'] }}%</span>
                        </div>
                        <div class="flex items-center justify-between pl-[18px]">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Rp{{ number_format($stat['rev'], 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $stat['cnt'] }} transaksi</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white dark:bg-[#1e1e21] rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h2 class="text-base font-semibold text-charcoal dark:text-gray-100">Transaksi Terbaru</h2>
                <a href="{{ route('admin.penjualan') }}" class="text-xs text-rose-500 hover:text-rose-600 font-medium transition-colors">Lihat Semua →</a>
            </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-xs text-gray-400 dark:text-gray-500 border-b border-gray-50 dark:border-gray-800">
                        <th class="px-6 py-3 text-left font-medium">ID</th>
                        <th class="px-6 py-3 text-left font-medium">Tanggal</th>
                        <th class="px-6 py-3 text-left font-medium">Kasir</th>
                        <th class="px-6 py-3 text-left font-medium">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiTerbaru as $t)
                    <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-3 text-sm text-charcoal dark:text-gray-100 font-medium">#{{ $t->transaction_id }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $t->user->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-charcoal dark:text-gray-100">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('paymentPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    backgroundColor: @json($chartColors),
                    borderWidth: 0,
                    hoverOffset: 4,
                    spacing: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                return context.label + ': Rp' + value.toLocaleString('id-ID') + ' (' + percent + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush

</x-layouts.admin>