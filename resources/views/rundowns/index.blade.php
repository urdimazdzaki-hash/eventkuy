@extends('layouts.app')
@section('title', 'Semua Rundown - EventKuy')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rundown Acara</h1>
        <p class="text-gray-500 dark:text-gray-400">Semua jadwal rundown dari seluruh acara kamu.</p>
    </div>

    @if ($events->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-10 text-center text-gray-400">
            Belum ada rundown. Tambahkan rundown saat membuat atau mengedit acara.
        </div>
    @else
        <div class="space-y-6">
            @foreach ($events as $event)
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($event->nama_event, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $event->nama_event }}</h2>
                                <p class="text-xs text-gray-400">{{ $event->tanggal_event->translatedFormat('d F Y') }} &middot; {{ ucfirst($event->tipe_lokasi) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('events.show', $event) }}"
                           class="text-xs text-coral hover:underline">Lihat Detail</a>
                    </div>

                    <div class="space-y-2">
                        @foreach ($event->rundowns->sortBy('waktu') as $item)
                            <div class="flex items-center gap-4 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                                <span class="text-sm font-medium text-coral w-16 flex-shrink-0">
                                    {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
                                </span>
                                <span class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ $item->kegiatan }}</span>
                                @if ($item->pic)
                                    <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-1 rounded-full flex-shrink-0">{{ $item->pic }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection