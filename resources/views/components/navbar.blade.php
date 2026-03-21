{{-- resources/views/components/navbar.blade.php --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-rose-100 transition-all duration-300" id="navbar">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <span class="font-display text-lg font-semibold tracking-wide text-charcoal leading-tight">
                    House of<br>
                    <span class="text-rose-600">Saraswati</span>
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="nav-link text-sm font-body font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors duration-200 {{ request()->routeIs('home') ? 'text-rose-600' : '' }}">
                    Beranda
                </a>
                <a href="{{ url('/') }}#tentang_kami_home"
                   class="nav-link text-sm font-body font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors duration-200 {{ request()->routeIs('hijab.*') ? 'text-rose-600' : '' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('katalog') }}"
                   class="nav-link text-sm font-body font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors duration-200 {{ request()->routeIs('katalog') ? 'text-rose-600' : '' }}">
                    Katalog
                </a>
                {{--  <a href="{{ route('aksesoris.index') }}"
                   class="nav-link text-sm font-body font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors duration-200 {{ request()->routeIs('aksesoris.*') ? 'text-rose-600' : '' }}">
                    
                </a>--}}
            </nav>

            {{-- CTA + Mobile Toggle --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('contact') }}"
                   class="hidden md:inline-flex items-center px-5 py-2 rounded-full border border-rose-400 text-rose-600 text-sm font-medium tracking-wide hover:bg-rose-600 hover:text-white transition-all duration-200">
                    Hubungi Kami
                </a>

                {{-- Mobile Menu Button --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md text-charcoal hover:text-rose-600 focus:outline-none" aria-label="Toggle menu">
                    <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-rose-100">
        <nav class="flex flex-col px-6 py-4 gap-4">
            <a href="{{ route('home') }}"
               class="text-sm font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors py-2 border-b border-rose-50">
                Beranda
            </a>
            <a href="{{ url('/') }}#tentang_kami_home"
               class="text-sm font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors py-2 border-b border-rose-50">
                Tentang Kami
            </a>
            <a href="{{ route('katalog') }}"
               class="text-sm font-medium tracking-widest uppercase text-charcoal hover:text-rose-600 transition-colors py-2 border-b border-rose-50">
                Katalog
            </a>
            <a href="{{ route('contact') }}"
               class="mt-2 text-center px-5 py-2.5 rounded-full bg-rose-600 text-white text-sm font-medium tracking-wide hover:bg-rose-700 transition-colors">
                Hubungi Kami
            </a>
        </nav>
    </div>
</header>

{{-- Spacer untuk fixed navbar --}}
<div class="h-16"></div>

<script>
    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });

    // Navbar scroll shadow
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
    });
</script>