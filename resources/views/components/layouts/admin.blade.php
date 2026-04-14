{{-- resources/views/components/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} – House of Saraswati</title>
    {{--  <script src="https://cdn.tailwindcss.com"></script>--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rose: { 400: '#fb7185', 500: '#f43f5e', 600: '#e11d48' },
                        charcoal: '#2c2c2c',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'serif'],
                        body: ['Jost', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>* { font-family: 'Jost', sans-serif; }</style>
    @stack('styles')
</head>
<body class="bg-[#f7f5f3] text-[#2c2c2c]">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-44 bg-white flex flex-col py-6 px-4 fixed top-0 left-0 h-full border-r border-gray-100 z-20">
        {{-- Brand --}}
        <div class="mb-10 px-2">
            <p class="font-body text-sm font-semibold text-charcoal leading-tight">House of<br>Saraswati.</p>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.dashboard') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h7v7H3zM14 3h7v5h-7zM14 12h7v9h-7zM3 18h7v3H3z"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.pesanan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.pesanan') || request()->routeIs('admin.transaksi.*') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>
            <a href="{{ route('admin.produk') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.produk*') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produk
            </a>
            <a href="{{ route('admin.penjualan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.penjualan*') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Penjualan
            </a>

            {{-- Divider --}}
            <div class="border-t border-gray-100 my-2"></div>

            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.users.*') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Users
            </a>
            @endif
            <a href="{{ route('admin.company-profile') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.company-profile*') ? 'bg-rose-500 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-charcoal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Profil
            </a>
        </nav>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:bg-red-50 hover:text-red-500 w-full transition-all">
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
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-end sticky top-0 z-10">
            <div class="flex items-center gap-2 text-sm font-medium text-charcoal">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>
                {{ Auth::user()->name ?? 'Admin' }}
                <span class="text-xs text-gray-400 font-normal">({{ ucfirst(Auth::user()->role ?? 'admin') }})</span>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>