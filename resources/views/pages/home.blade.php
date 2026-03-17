{{-- resources/views/pages/home.blade.php --}}
<x-layouts.app title="House of Saraswati – Koleksi Hijab & Gamis Terbaru">

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="relative min-h-[88vh] flex items-center overflow-hidden bg-cream">
        {{-- Background image --}}
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('images/hero_image.png') }}"
                alt="Hero Background"
                class="w-full h-full object-cover object-center"
            >
            <div class="hero-overlay absolute inset-0"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 max-w-6xl px-6 lg:px-8 py-20 mx-40">
            <div class="max-w-lg">
                <h1 class="font-display text-5xl lg:text-6xl font-semibold text-charcoal leading-tight mb-5 animate-fade-up">
                    Koleksi Hijab &<br>
                    <span class="italic text-rose-600">Gamis Terbaru</span>
                </h1>
                <p class="text-gray-600 text-base leading-relaxed mb-8 animate-fade-up delay-100">
                    Temukan hijab dan gamis pilihan dengan desain yang lembut, elegan, dan nyaman untuk menemani setiap langkah Anda.
                </p>
                <a href="{{ route('products.index') }}"
                   class="animate-fade-up delay-200 inline-flex items-center gap-2 px-7 py-3 rounded-full border border-rose-400 text-rose-600 text-sm font-medium tracking-wide hover:bg-rose-600 hover:text-white transition-all duration-300 group">
                    Mulai Jelajahi
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== TENTANG KAMI ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <h2 class="font-display text-3xl font-semibold text-center text-charcoal mb-14 tracking-wide">Tentang Kami</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                {{-- Image --}}
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img
                        src="{{ asset('images/tentang_kami.png') }}"
                        alt="House of Saraswati – Koleksi Kami"
                        class="w-full h-80 md:h-96 object-cover"
                    >
                </div>

                {{-- Text --}}
                <div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        House of Saraswati merupakan UMKM fashion muslimah yang menghadirkan koleksi hijab dan gamis dengan desain elegan serta material berkualitas. Kami percaya bahwa setiap perempuan berhak tampil anggun dan percaya diri dalam setiap kesempatan.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Dengan komitmen pada kualitas dan detail, setiap produk kami dipilih dan dikemas dengan penuh perhatian. House of Saraswati berlokasi di Jakarta dan terus berkembang untuk menjangkau lebih banyak pelanggan melalui platform digital.
                    </p>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center px-6 py-2.5 rounded-full border border-rose-400 text-rose-600 text-sm font-medium hover:bg-rose-600 hover:text-white transition-all duration-200">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KOLEKSI UNGGULAN ===================== --}}
    <section class="py-20 bg-[#f5eeea]">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <h2 class="font-display text-3xl font-semibold text-center text-charcoal mb-14 tracking-wide">Koleksi Unggulan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Card: Hijab --}}
                <a href="{{ route('hijab.index') }}" class="product-card group block rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="overflow-hidden h-64">
                        <img
                            src="{{ asset('images/hijab_unggulan.png') }}"
                            alt="Koleksi Hijab"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="p-4 text-center">
                        <span class="text-sm font-medium tracking-widest uppercase text-charcoal group-hover:text-rose-600 transition-colors">Hijab</span>
                    </div>
                </a>

                {{-- Card: Gamis --}}
                <a href="{{ route('gamis.index') }}" class="product-card group block rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="overflow-hidden h-64">
                        <img
                            src="{{ asset('images/collection-gamis.jpg') }}"
                            alt="Koleksi Gamis"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="p-4 text-center">
                        <span class="text-sm font-medium tracking-widest uppercase text-charcoal group-hover:text-rose-600 transition-colors">Gamis</span>
                    </div>
                </a>

                {{-- Card: Aksesoris --}}
                <a href="{{ route('aksesoris.index') }}" class="product-card group block rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="overflow-hidden h-64">
                        <img
                            src="{{ asset('images/collection-aksesoris.jpg') }}"
                            alt="Koleksi Aksesoris"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="p-4 text-center">
                        <span class="text-sm font-medium tracking-widest uppercase text-charcoal group-hover:text-rose-600 transition-colors">Aksesoris</span>
                    </div>
                </a>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-8 py-3 rounded-full border border-charcoal text-charcoal text-sm font-medium tracking-wide hover:bg-charcoal hover:text-white transition-all duration-200">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== MENGAPA HOUSE OF SARASWATI ===================== --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <h2 class="font-body text-base font-semibold text-center text-charcoal mb-10 tracking-wide">Mengapa House of Saraswati</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                {{-- Kiri: Daftar fitur dengan garis bawah --}}
                <div>
                    @php
                        $features = ['Material Unggulan', 'Detail & Kualitas', 'Desain Timeless', 'Layanan Ramah'];
                    @endphp
                    @foreach ($features as $feature)
                    <div class="py-5 border-b border-gray-200">
                        <h3 class="font-display text-2xl font-light text-charcoal">{{ $feature }}</h3>
                    </div>
                    @endforeach
                </div>

                {{-- Kanan: Gambar dengan border pink melengkung di kanan --}}
                <div class="flex justify-center md:justify-end">
                    <div class="relative w-64 h-72">
                        {{-- Border pink kanan --}}
                        <div class="absolute right-0 top-0 bottom-0 w-4 bg-rose-400 rounded-r-3xl rounded-l-none"></div>
                        {{-- Gambar --}}
                        <div class="absolute inset-0 right-2 overflow-hidden rounded-2xl">
                            <img
                                src="{{ asset('images/mengapa_image.png') }}"
                                alt="Kualitas House of Saraswati"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="py-10 px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="relative bg-[#f5eeea] rounded-2xl overflow-hidden flex items-center min-h-[160px]">

                {{-- Kiri: Teks + Tombol --}}
                <div class="flex-1 px-8 py-8 z-10">
                    <p class="text-base font-body text-charcoal mb-1">
                        Temukan <span class="font-semibold text-rose-500">koleksi terbaik</span> untuk
                    </p>
                    <p class="text-base font-body text-charcoal mb-5">gaya Anda hari ini.</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('products.index') }}"
                           class="px-5 py-2 rounded-full border border-gray-400 text-gray-700 text-sm font-medium hover:bg-gray-100 transition-colors">
                            Lihat Katalog
                        </a>
                        <a href="{{ route('contact') }}"
                           class="px-5 py-2 rounded-full bg-rose-500 text-white text-sm font-medium hover:bg-rose-600 transition-colors">
                            Hubungi Sekarang
                        </a>
                    </div>
                </div>

                {{-- Kanan: Gambar overflow ke atas --}}
                <div class="hidden md:block absolute right-0 top-0 bottom-0 w-72">
                    <img
                        src="{{ asset('images/cta-image.jpg') }}"
                        alt="Koleksi Terbaik"
                        class="w-full h-full object-cover object-left rounded-r-2xl"
                    >
                </div>

            </div>
        </div>
    </section>

</x-layouts.app>