{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-[#3d3535] text-white rounded-t-3xl overflow-hidden">

    {{-- Top Section: Heading + Deskripsi --}}
    <div class="max-w-6xl mx-auto px-8 pt-12 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start border-b border-white/10 pb-10">

            {{-- Heading Kiri --}}
            <div>
                <h2 class="font-display text-4xl lg:text-5xl font-semibold leading-tight">
                    Temukan Gaya Modest Anda Bersama Kami
                </h2>
            </div>

            {{-- Deskripsi Kanan --}}
            <div class="flex items-start md:justify-end">
                <p class="text-sm text-gray-300 leading-relaxed max-w-xs italic">
                    House of Saraswati menghadirkan koleksi hijab dan gamis dengan desain elegan dan nyaman, dirancang untuk menemani setiap langkah perempuan modern dengan penuh percaya diri dan keanggunan.
                </p>
            </div>

        </div>
    </div>

    {{-- Bottom Info: 4 Kolom --}}
    <div class="max-w-6xl mx-auto px-8 pb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

            {{-- Kolom 1: Logo/Brand --}}
            <div>
                <p class="text-rose-400 font-semibold text-base leading-snug">
                    House of<br>Saraswati.
                </p>
            </div>

            {{-- Kolom 2: Alamat --}}
            <div>
                <h4 class="font-semibold text-white text-sm mb-2">Alamat</h4>
                <p class="text-gray-300 text-sm">Jakarta, Indonesia</p>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div>
                <h4 class="font-semibold text-white text-sm mb-2">Kontak</h4>
                <div class="space-y-1 text-sm text-gray-300">
                    <p>+62 812 1188 2222</p>
                    <a href="mailto:houseofsaraswati@gmail.com"
                       class="flex items-center gap-1 hover:text-white transition-colors">
                        houseofsaraswati@gmail.com
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    <p>WhatsApp Available</p>
                </div>
            </div>

            {{-- Kolom 4: Navigasi --}}
            <div>
                <h4 class="font-semibold text-white text-sm mb-2">Navigasi</h4>
                <ul class="space-y-1 text-sm text-gray-300">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors underline underline-offset-2">Beranda</a></li>
                    <li><a href="{{ route('hijab.index') }}" class="hover:text-white transition-colors underline underline-offset-2">Tentang Kami</a></li>
                    <li><a href="{{ route('gamis.index') }}" class="hover:text-white transition-colors underline underline-offset-2">Katalog</a></li>
                    {{-- <li><a href="{{ route('aksesoris.index') }}" class="hover:text-white transition-colors underline underline-offset-2">Aksesoris</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors underline underline-offset-2">Kontak</a></li> --}}
                </ul>
            </div>

        </div>
    </div>

    {{-- Copyright Bar: Pink --}}
    <div class="bg-rose-500 px-8 py-3">
        <p class="text-white text-xs text-left">
            ©{{ date('Y') }} House of Saraswati. All rights reserved.
        </p>
    </div>

</footer>