<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'House of Saraswati' }}</title>
    <meta name="description" content="{{ $description ?? 'Koleksi Hijab & Gamis Terbaru - House of Saraswati' }}">

    {{-- <script src="https://cdn.tailwindcss.com"></script>--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rose: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                        },
                        blush: '#f9e8e8',
                        cream: '#faf7f4',
                        charcoal: '#2c2c2c',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'serif'],
                        body: ['Jost', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Jost', sans-serif; }
        h1, h2, h3 { font-family: 'Cormorant Garamond', serif; }

        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #e11d48;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }

        .hero-overlay {
            background: linear-gradient(to right, rgba(255,255,255,0.92) 40%, rgba(255,255,255,0.3) 100%);
        }

        .product-card:hover img {
            transform: scale(1.05);
        }
        .product-card img {
            transition: transform 0.4s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeInUp 0.7s ease forwards;
        }
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }

        html {
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-charcoal antialiased">

    {{-- Navbar Component --}}
    <x-navbar />

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer Component --}}
    <x-footer />

    @stack('scripts')
</body>
</html>