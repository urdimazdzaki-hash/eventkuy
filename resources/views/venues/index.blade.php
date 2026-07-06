@extends('layouts.app')
@section('title', 'Data Venue')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Venue</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">Kelola seluruh data venue acara.</p>
        </div>
        <a href="{{ route('venues.create') }}"
            class="flex items-center gap-1.5 bg-coral hover:bg-red-400 text-white text-sm font-semibold px-4 py-2 rounded-full transition">
            <i class="ph-bold ph-plus text-xs"></i> Tambah Venue
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($venues->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-16 text-center">
            <i class="ph-duotone ph-buildings text-5xl text-gray-200 dark:text-gray-700 mb-4 block"></i>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Belum ada data venue</p>
            <p class="text-gray-400 dark:text-gray-600 text-xs mb-6">Tambahkan venue pertama untuk mulai mengelola lokasi acara</p>
            <a href="{{ route('venues.create') }}"
                class="inline-flex items-center gap-1.5 bg-coral hover:bg-red-400 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
                <i class="ph-bold ph-plus text-xs"></i> Tambah Venue
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($venues as $venue)
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden hover:shadow-md hover:border-coral/30 transition-all duration-300 group">

                    <div class="h-3 bg-gradient-to-r from-coral to-red-400"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($venue->nama_venue, 0, 1)) }}
                            </div>
                            <span class="text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded-lg font-medium">
                                {{ number_format($venue->kapasitas) }} orang
                            </span>
                        </div>

                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $venue->nama_venue }}</h3>

                        <div class="flex items-start gap-1.5 mb-3">
                            <i class="ph-duotone ph-map-pin text-sm text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $venue->alamat }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800">
                            <div>
                                <p class="text-xs text-gray-400">Harga</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($venue->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('venues.edit', $venue->id) }}"
                                    class="w-8 h-8 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-500 hover:bg-yellow-100 flex items-center justify-center transition"
                                    title="Edit">
                                    <i class="ph-duotone ph-pencil-simple text-sm"></i>
                                </a>
                                <form action="{{ route('venues.destroy', $venue->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        onclick="return confirm('Yakin ingin menghapus venue ini?')"
                                        class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-400 hover:bg-red-100 flex items-center justify-center transition"
                                        title="Hapus">
                                        <i class="ph-duotone ph-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection