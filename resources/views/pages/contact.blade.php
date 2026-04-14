<x-layouts.app title="Kontak Kami – House of Saraswati">

    {{-- Hero Section --}}
    <section class="relative w-full h-64 overflow-hidden mb-16">
        <div class="absolute inset-0 bg-rose-50 flex items-center justify-center">
            <h1 class="font-display text-4xl lg:text-5xl font-semibold text-rose-500 tracking-wide text-center px-4">Hubungi Kami</h1>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 lg:px-8 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 text-charcoal">
            
            {{-- Kiri: Info Kontak --}}
            <div>
                <h2 class="text-2xl font-bold mb-6">Kami Siap Membantu Anda</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Punya pertanyaan tentang produk, pengiriman, atau ingin berkonsultasi mengenai gaya Anda? Jangan ragu untuk menghubungi kami melalui informasi di bawah ini.
                </p>

                <div class="space-y-6">
                    {{-- Alamat --}}
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-rose-500 shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Alamat</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $profile->address ?? 'Jakarta, Indonesia' }}</p>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-rose-500 shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">WhatsApp</h3>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->phone ?? '6281211882222') }}" target="_blank" class="text-rose-500 hover:text-rose-600 text-sm transition-colors">{{ $profile->phone ?? '+62 812-1188-2222' }}</a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-rose-500 shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Email</h3>
                            <a href="mailto:{{ $profile->email ?? 'hello@saraswati.com' }}" class="text-rose-500 hover:text-rose-600 text-sm transition-colors">{{ $profile->email ?? 'hello@saraswati.com' }}</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Form (Static untuk UI Only sesuai figma/requirement) --}}
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-50">
                <h3 class="text-xl font-bold mb-6 text-charcoal">Kirim Pesan</h3>
                <form class="space-y-4">
                    <div>
                        <input type="text" placeholder="Nama Lengkap" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-400/20 transition-all text-sm shadow-inner">
                    </div>
                    <div>
                        <input type="email" placeholder="Alamat Email" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-400/20 transition-all text-sm shadow-inner">
                    </div>
                    <div>
                        <input type="text" placeholder="Subjek" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-400/20 transition-all text-sm shadow-inner">
                    </div>
                    <div>
                        <textarea placeholder="Pesan Anda" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-400/20 transition-all text-sm shadow-inner resize-none"></textarea>
                    </div>
                    <button type="button" class="w-full bg-rose-500 text-white font-medium py-3 rounded-xl hover:bg-rose-600 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        Kirim Sekarang
                    </button>
                </form>
            </div>
            
        </div>
    </section>

</x-layouts.app>
