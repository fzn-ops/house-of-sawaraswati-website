<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak – House of Saraswati</title>
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
    <style>
        * { font-family: 'Jost', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 100%);
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            border-radius: 50%;
            animation: pulse 10s infinite alternate;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.2); opacity: 0.4; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center relative overflow-hidden text-charcoal">

    {{-- Decor Blobs --}}
    <div class="blob bg-rose-300 w-96 h-96 top-[-10%] left-[-10%]"></div>
    <div class="blob bg-purple-300 w-[500px] h-[500px] bottom-[-20%] right-[-10%]"></div>

    <div class="glass-panel p-12 rounded-3xl shadow-2xl max-w-md w-full text-center relative z-10 mx-4 transform transition-all hover:scale-105 duration-300">
        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        
        <h1 class="font-display text-6xl font-bold text-rose-500 mb-2">403</h1>
        <h2 class="text-xl font-bold mb-4">Akses Ditolak</h2>
        <p class="text-gray-500 text-sm mb-8 leading-relaxed">
            Maaf, halaman ini terbatas. Hanya <span class="font-semibold text-rose-500">Administrator</span> yang memiliki hak akses untuk masuk ke area ini.
        </p>

        <a href="javascript:history.back()" class="inline-flex items-center justify-center w-full px-6 py-3.5 bg-charcoal text-white rounded-xl font-medium text-sm hover:bg-black hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Halaman Sebelumnya
        </a>
        
        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-rose-500 hover:text-rose-600 font-medium underline-offset-4 hover:underline transition-all">
                Kembali ke Dashboard Utama
            </a>
        </div>
    </div>

</body>
</html>
