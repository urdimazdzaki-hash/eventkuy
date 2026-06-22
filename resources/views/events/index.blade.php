@extends('layouts.app')
@section('title', 'Dashboard - EventKuy')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Halo, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-gray-500">Berikut daftar event kamu.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-coral hover:underline">
                Logout
            </button>
        </form>
    </div>

    <a href="{{ route('events.create') }}"
       class="inline-block bg-coral hover:bg-red-400 text-white font-semibold px-6 py-3 rounded-full mb-8 transition">
        + Buat Event Baru
    </a>

    @if ($events->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-500">
            Belum ada event. Yuk buat event pertamamu!
        </div>
    @else
        <div class="grid gap-4">
            @foreach ($events as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="block bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800">{{ $event->nama_event }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($event->jenis_event) }} &middot;
                                {{ $event->tanggal_event->translatedFormat('d F Y') }}
                                @if ($event->lokasi_venue)
                                    &middot; {{ $event->lokasi_venue }}
                                @endif
                            </p>
                        </div>

                        @if ($event->butuh_cek_cuaca)
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full whitespace-nowrap">
                                H-{{ $event->hari_menuju_event }} • Cek cuaca
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection