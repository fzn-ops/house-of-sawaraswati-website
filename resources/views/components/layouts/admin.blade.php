{{-- resources/views/components/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} – House of Saraswati</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Anti-FOUC: set dark class sebelum render --}}
    <script>
        (function() {
            const theme = localStorage.getItem('admin-theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>* { font-family: 'Jost', sans-serif; }</style>

    {{-- Midtrans Snap.js --}}
    @php $midtransClientKey = config('midtrans.client_key'); @endphp
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey }}"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(options) {
            const opts = Object.assign({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
            }, options || {});
            return Swal.fire({
                title: opts.title,
                text: opts.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: opts.confirmButtonText,
                cancelButtonText: opts.cancelButtonText,
                reverseButtons: true,
            });
        }

        function confirmDeleteForm(form, options) {
            confirmDelete(options).then((result) => {
                if (result.isConfirmed) form.submit();
            });
            return false;
        }
    </script>

    @stack('styles')
</head>
<body class="bg-[#f7f5f3] dark:bg-[#111113] text-[#2c2c2c] dark:text-gray-200">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-44 bg-white dark:bg-[#1a1a1d] flex flex-col py-6 px-4 fixed top-0 left-0 h-full border-r border-gray-100 dark:border-gray-800 z-20">
        {{-- Brand --}}
        <div class="mb-10 px-2">
            <p class="font-body text-sm font-semibold text-charcoal dark:text-gray-100 leading-tight">House of<br>Saraswati.</p>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-1">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'check' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 7h7v7H3zM14 3h7v5h-7zM14 12h7v9h-7zM3 18h7v3H3z'],
                    ['route' => 'admin.pesanan', 'check' => 'admin.pesanan|admin.transaksi.*', 'label' => 'Pesanan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['route' => 'admin.produk', 'check' => 'admin.produk*', 'label' => 'Produk', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['route' => 'admin.penjualan', 'check' => 'admin.penjualan*', 'label' => 'Penjualan', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                ];
                $isActive = function($check) {
                    foreach (explode('|', $check) as $pattern) {
                        if (request()->routeIs(trim($pattern))) return true;
                    }
                    return false;
                };
            @endphp

            @foreach ($navItems as $nav)
            <a href="{{ route($nav['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ $isActive($nav['check']) ? 'bg-rose-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-charcoal dark:hover:text-gray-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $nav['icon'] }}"/>
                </svg>
                {{ $nav['label'] }}
            </a>
            @endforeach

            {{-- Divider --}}
            <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>

            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.users.*') ? 'bg-rose-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-charcoal dark:hover:text-gray-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Users
            </a>
            @endif
            <a href="{{ route('admin.company-profile') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.company-profile*') ? 'bg-rose-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-charcoal dark:hover:text-gray-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Profil
            </a>
        </nav>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 w-full transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </aside>

    {{-- Main --}}
    <div class="flex-1 ml-44 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-[#1a1a1d] border-b border-gray-100 dark:border-gray-800 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            {{-- Dark Mode Toggle --}}
            <button onclick="toggleDarkMode()" id="dark-mode-toggle"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 dark:hover:text-rose-400 transition-all duration-200 text-xs font-medium">
                {{-- Sun icon (tampil saat dark) --}}
                <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{-- Moon icon (tampil saat light) --}}
                <svg class="w-4 h-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span class="dark:hidden">Dark</span>
                <span class="hidden dark:inline">Light</span>
            </button>

            {{-- User info --}}
            <div class="flex items-center gap-2 text-sm font-medium text-charcoal dark:text-gray-200">
                <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>
                {{ Auth::user()->name ?? 'Admin' }}
                <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">({{ ucfirst(Auth::user()->role ?? 'admin') }})</span>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Dark Mode Toggle Script --}}
<script>
    function toggleDarkMode() {
        const html = document.documentElement;
        html.classList.add('dark-transition');
        html.classList.toggle('dark');

        const isDark = html.classList.contains('dark');
        localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');

        // Hapus transition class setelah animasi selesai
        setTimeout(() => html.classList.remove('dark-transition'), 350);
    }
</script>

@stack('scripts')
</body>
</html>