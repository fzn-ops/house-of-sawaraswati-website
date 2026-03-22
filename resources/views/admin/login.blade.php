<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin – House of Saraswati</title>
    {{--  <script src="https://cdn.tailwindcss.com"></script>--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
        h1, h2 { font-family: 'Cormorant Garamond', serif; }

        .bg-pattern {
            background-color: #f7f5f3;
            background-image: radial-gradient(#fda4af22 1px, transparent 1px);
            background-size: 24px 24px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp 0.6s ease forwards; }
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm">

        {{-- Brand --}}
        <div class="text-center mb-8 animate-fade-up">
            <h1 class="text-3xl font-semibold text-[#2c2c2c]">House of</h1>
            <h1 class="text-3xl font-semibold text-rose-500 italic">Saraswati.</h1>
            <p class="text-xs text-gray-400 mt-2 tracking-widest uppercase">Admin Panel</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 animate-fade-up delay-100">

            <h2 class="text-xl font-semibold text-[#2c2c2c] mb-6">Masuk ke Dashboard</h2>

            {{-- Error message --}}
            @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-600">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 tracking-wide uppercase">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@saraswati.com"
                        required
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors placeholder-gray-300 @error('email') border-rose-400 @enderror"
                    >
                    @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 tracking-wide uppercase">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password-input"
                            placeholder="••••••••"
                            required
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors placeholder-gray-300 pr-10 @error('password') border-rose-400 @enderror"
                        >
                        {{-- Toggle show/hide password --}}
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-rose-500 text-white text-sm font-semibold rounded-xl hover:bg-rose-600 active:scale-95 transition-all duration-200">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6 animate-fade-up delay-200">
            &copy; {{ date('Y') }} House of Saraswati. All rights reserved.
        </p>

    </div>

    <script>
        function togglePassword() {
            const input     = document.getElementById('password-input');
            const eyeOpen   = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            const isHidden  = input.type === 'password';
            input.type      = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        }
    </script>

</body>
</html>