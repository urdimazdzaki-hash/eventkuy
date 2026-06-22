@extends('layouts.app')
@section('title', 'Daftar - EventKuy')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-20 px-6 py-10 max-w-6xl mx-auto">

    {{-- Foto wedding, miring, rounded --}}
    <div class="hidden lg:block w-full max-w-sm">
        <img src="{{ asset('images/wedding-hero.jpg') }}"
             alt="Outdoor wedding setup"
             class="rounded-[2rem] shadow-xl -rotate-3 w-full h-[520px] object-cover">
    </div>

    {{-- Form Register --}}
    <div class="w-full max-w-md">

        <div class="text-center mb-10">
            <h1 class="text-3xl">
                <span class="font-script italic text-gray-800">Event</span><span class="font-bold text-gray-800">kuy</span>
            </h1>
        </div>

        <h2 class="text-3xl font-bold text-center mb-2">Create Exceptional Events</h2>
        <p class="text-center text-gray-500 mb-6">Buat akun baru di EventKuy</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <input type="text" name="name" placeholder="Nama lengkap" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral">
            </div>

            <div>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral">
            </div>

            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Password"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-coral">
                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-coral">
                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            <button type="submit"
                    class="w-full bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-full transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-coral hover:underline">Masuk</a>
        </p>

    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection