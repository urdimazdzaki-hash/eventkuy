@extends('layouts.app')
@section('title', 'Login - EventKuy')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 overflow-hidden">

    <div class="relative w-full max-w-4xl min-h-[560px] bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden flex">

        {{-- PANEL KIRI: LOGIN --}}
        <div id="panel-login" class="w-full md:w-1/2 px-10 py-12 flex flex-col justify-center transition-all duration-500 absolute md:relative inset-0">
            <div class="text-center mb-8">
                <h1 class="text-3xl mb-1">
                    <span class="font-script italic text-gray-800 dark:text-gray-100">Event</span><span class="font-bold text-gray-800 dark:text-gray-100">kuy</span>
                </h1>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-2">Selamat Datang</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk ke akun EventKuy kamu</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-lg px-4 py-3 mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral text-sm">

                <div class="relative">
                    <input type="password" id="login-password" name="password" placeholder="Password"
                           class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-coral text-sm">
                    <button type="button" onclick="togglePassword('login-password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <button type="submit"
                        class="w-full bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-xl transition text-sm">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Belum punya akun?
                <button onclick="switchToRegister()" class="text-coral hover:underline font-medium">Daftar</button>
            </p>
        </div>

        {{-- PANEL KANAN: REGISTER --}}
        <div id="panel-register" class="w-full md:w-1/2 px-10 py-12 flex flex-col justify-center transition-all duration-500 absolute md:relative inset-0 translate-x-full opacity-0 pointer-events-none">
            <div class="text-center mb-6">
                <h1 class="text-3xl mb-1">
                    <span class="font-script italic text-gray-800 dark:text-gray-100">Event</span><span class="font-bold text-gray-800 dark:text-gray-100">kuy</span>
                </h1>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-2">Buat Akun Baru</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bergabung dengan EventKuy sekarang</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Nama lengkap" value="{{ old('name') }}"
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral text-sm">

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                       class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral text-sm">

                <div class="relative">
                    <input type="password" id="reg-password" name="password" placeholder="Password"
                           class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-coral text-sm">
                    <button type="button" onclick="togglePassword('reg-password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="relative">
                    <input type="password" id="reg-confirm" name="password_confirmation" placeholder="Konfirmasi password"
                           class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-coral text-sm">
                    <button type="button" onclick="togglePassword('reg-confirm')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <button type="submit"
                        class="w-full bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-xl transition text-sm">
                    Daftar
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-5">
                Sudah punya akun?
                <button onclick="switchToLogin()" class="text-coral hover:underline font-medium">Masuk</button>
            </p>
        </div>

        {{-- PANEL GAMBAR GESER --}}
        <div id="panel-image" class="hidden md:flex w-1/2 bg-coral flex-col items-center justify-center p-10 absolute right-0 top-0 bottom-0 transition-all duration-500 z-10">
            <img src="{{ asset('images/wedding-hero.jpg') }}"
                 alt="Wedding"
                 class="rounded-2xl w-full h-full object-cover opacity-60 absolute inset-0">
            <div class="relative z-10 text-center text-white">
                <h2 id="image-title" class="text-2xl font-bold mb-2">Belum punya akun?</h2>
                <p id="image-sub" class="text-sm opacity-90 mb-6">Daftar sekarang dan mulai kelola acaramu</p>
                <button id="image-btn" onclick="switchToRegister()"
                    class="border-2 border-white text-white font-semibold px-6 py-2 rounded-full hover:bg-white hover:text-coral transition text-sm">
                    Daftar
                </button>
            </div>
        </div>

    </div>
</div>

<style>
    #panel-login, #panel-register {
        transition: transform 0.5s cubic-bezier(0.77,0,0.175,1), opacity 0.5s ease;
    }
    #panel-image {
        transition: transform 0.5s cubic-bezier(0.77,0,0.175,1);
    }
</style>

<script>
    function switchToRegister() {
        document.getElementById('panel-login').style.transform = 'translateX(-100%)';
        document.getElementById('panel-login').style.opacity = '0';
        document.getElementById('panel-login').style.pointerEvents = 'none';

        document.getElementById('panel-register').style.transform = 'translateX(0)';
        document.getElementById('panel-register').style.opacity = '1';
        document.getElementById('panel-register').style.pointerEvents = 'auto';

        document.getElementById('panel-image').style.transform = 'translateX(-100%)';

        document.getElementById('image-title').textContent = 'Sudah punya akun?';
        document.getElementById('image-sub').textContent = 'Masuk dan lanjutkan perjalananmu';
        document.getElementById('image-btn').textContent = 'Masuk';
        document.getElementById('image-btn').setAttribute('onclick', 'switchToLogin()');
    }

    function switchToLogin() {
        document.getElementById('panel-register').style.transform = 'translateX(100%)';
        document.getElementById('panel-register').style.opacity = '0';
        document.getElementById('panel-register').style.pointerEvents = 'none';

        document.getElementById('panel-login').style.transform = 'translateX(0)';
        document.getElementById('panel-login').style.opacity = '1';
        document.getElementById('panel-login').style.pointerEvents = 'auto';

        document.getElementById('panel-image').style.transform = 'translateX(0)';

        document.getElementById('image-title').textContent = 'Belum punya akun?';
        document.getElementById('image-sub').textContent = 'Daftar sekarang dan mulai kelola acaramu';
        document.getElementById('image-btn').textContent = 'Daftar';
        document.getElementById('image-btn').setAttribute('onclick', 'switchToRegister()');
    }

    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    @if(request()->routeIs('register') || session('show_register'))
        switchToRegister();
    @endif
</script>
@endsection