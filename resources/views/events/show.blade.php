@extends('layouts.app')
@section('title', $event->nama_event . ' - EventKuy')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-8">

    <div class="relative bg-gradient-to-r from-coral to-red-400 rounded-2xl p-8 mb-6 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-72 h-72 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
        </div>
        <div class="relative z-10">
            <a href="{{ route('events.index') }}" class="text-white/70 hover:text-white text-sm mb-4 inline-block">&larr; Kembali ke Dashboard</a>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $event->nama_event }}</h1>
                    <div class="flex flex-wrap gap-3 text-white/80 text-sm">
                        <span class="flex items-center gap-1">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            {{ ucfirst($event->jenis_event) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            {{ $event->lokasi_venue ?? 'Venue belum diset' }}{{ $event->kota_venue ? ', ' . $event->kota_venue : '' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            {{ $event->tanggal_event->translatedFormat('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            H-{{ $event->hari_menuju_event }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('events.edit', $event) }}"
                       class="px-4 py-2 rounded-full bg-white/20 hover:bg-white/30 text-white text-sm font-medium transition">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Yakin hapus acara ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-full bg-white/20 hover:bg-red-500 text-white text-sm font-medium transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $checklist = [
            ['label' => 'Nama acara', 'done' => (bool) $event->nama_event],
            ['label' => 'Tanggal acara', 'done' => (bool) $event->tanggal_event],
            ['label' => 'Venue & kota', 'done' => (bool) $event->lokasi_venue && (bool) $event->kota_venue],
            ['label' => 'Jumlah tamu', 'done' => (bool) $event->jumlah_tamu],
            ['label' => 'Rundown acara', 'done' => $event->rundowns->isNotEmpty()],
            ['label' => 'Item anggaran', 'done' => $event->budgetItems->isNotEmpty() || (bool) $event->jumlah_tamu],
        ];
        $done = collect($checklist)->where('done', true)->count();
        $total = count($checklist);
        $persen = round(($done / $total) * 100);
    @endphp

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Progress Persiapan</h2>
            <span class="text-sm font-semibold text-coral">{{ $persen }}%</span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 mb-4">
            <div class="bg-coral h-2.5 rounded-full transition-all" style="width: {{ $persen }}%"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach ($checklist as $item)
                <div class="flex items-center gap-2 text-sm {{ $item['done'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}">
                    @if ($item['done'])
                        <span class="text-green-500">✓</span>
                    @else
                        <span class="text-gray-300 dark:text-gray-600">○</span>
                    @endif
                    {{ $item['label'] }}
                </div>
            @endforeach
        </div>
    </div>

    @if ($event->butuh_cek_cuaca)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-5 mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="cloud-rain" class="w-5 h-5 text-yellow-600"></i>
                <h3 class="font-semibold text-yellow-800 dark:text-yellow-400">Peringatan: H-{{ $event->hari_menuju_event }}</h3>
            </div>
            <p class="text-sm text-yellow-700 dark:text-yellow-500">
                Acara outdoor ini sudah mendekati H-3. Laporan kesiapan cuaca akan tampil di sini (menunggu integrasi modul Mhs 3).
            </p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Detail Acara</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Jenis Acara</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ ucfirst($event->jenis_event) }}</p>
            </div>
            <div>
                <p class="text-gray-400">Lokasi</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ ucfirst($event->tipe_lokasi) }}</p>
            </div>
            <div>
                <p class="text-gray-400">Tanggal</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ $event->tanggal_event->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Venue</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ $event->lokasi_venue ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Kota</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ $event->kota_venue ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Jumlah Tamu</p>
                <p class="text-gray-800 dark:text-gray-100 font-medium">{{ $event->jumlah_tamu ? number_format($event->jumlah_tamu, 0, ',', '.') . ' pax' : '-' }}</p>
            </div>
        </div>
        @if ($event->catatan)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <p class="text-gray-400 text-sm mb-1">Catatan</p>
                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $event->catatan }}</p>
            </div>
        @endif
    </div>

    @if ($event->nama_paket && $event->fasilitas_paket)
        <div class="bg-amber-50 dark:bg-gray-900 border border-amber-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">Fasilitas Paket</h2>
            <p class="text-sm text-amber-700 dark:text-amber-400 font-medium mb-3">✨ Paket {{ ucfirst($event->nama_paket) }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                @foreach ($event->daftar_fasilitas as $fasilitas)
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <span class="text-green-500">✓</span>
                        <span>{{ $fasilitas }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Checklist Persiapan</h2>
            <span class="text-xs text-gray-400">{{ $event->checklists->where('selesai', true)->count() }} / {{ $event->checklists->count() }} selesai</span>
        </div>

        <form method="POST" action="{{ route('checklists.store', $event) }}" class="flex gap-2 mb-4">
            @csrf
            <input type="text" name="tugas" placeholder="Tambah tugas baru..." required
                   class="flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <button type="submit"
                    class="bg-coral hover:bg-red-400 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                + Tambah
            </button>
        </form>

        @if ($event->checklists->isEmpty())
            <p class="text-sm text-gray-400">Belum ada checklist. Tambahkan tugas persiapan di atas.</p>
        @else
            <div class="space-y-2">
                @foreach ($event->checklists as $item)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                        <form method="POST" action="{{ route('checklists.toggle', [$event, $item]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition
                                    {{ $item->selesai ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 dark:border-gray-600' }}">
                                @if ($item->selesai)
                                    <span class="text-xs">✓</span>
                                @endif
                            </button>
                        </form>
                        <span class="flex-1 text-sm {{ $item->selesai ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $item->tugas }}
                        </span>
                        <form method="POST" action="{{ route('checklists.destroy', [$event, $item]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-300 dark:text-gray-600 hover:text-red-500 text-sm">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Rundown Acara</h2>

        @if ($event->rundowns->isEmpty())
            <p class="text-sm text-gray-400">Belum ada rundown untuk acara ini.</p>
        @else
            <div class="space-y-2">
                @foreach ($event->rundowns as $item)
                    <div class="flex items-center gap-4 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100 w-16">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ $item->kegiatan }}</span>
                        @if ($item->pic)
                            <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-1 rounded-full">{{ $item->pic }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Anggaran</h2>

        @if ($event->jumlah_tamu && $event->harga_per_orang)
            <div class="flex justify-between text-sm py-2 border-b border-gray-50 dark:border-gray-800">
                <span class="text-gray-700 dark:text-gray-300">
                    Catering ({{ number_format($event->jumlah_tamu, 0, ',', '.') }} pax &times; Rp {{ number_format($event->harga_per_orang, 0, ',', '.') }})
                </span>
                <span class="font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($event->subtotal_catering, 0, ',', '.') }}</span>
            </div>
        @endif

        @forelse ($event->budgetItems as $item)
            <div class="flex justify-between text-sm py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                <span class="text-gray-700 dark:text-gray-300">{{ $item->nama_item }}</span>
                <span class="font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($item->estimasi_biaya, 0, ',', '.') }}</span>
            </div>
        @empty
            @if (!$event->jumlah_tamu)
                <p class="text-sm text-gray-400">Belum ada item anggaran untuk acara ini.</p>
            @endif
        @endforelse

        <div class="flex justify-between text-base font-semibold text-gray-800 dark:text-gray-100 pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
            <span>Total Anggaran</span>
            <span>Rp {{ number_format($event->total_anggaran, 0, ',', '.') }}</span>
        </div>
    </div>

</div>
@endsection