@extends('layouts.app')
@section('title', 'Dashboard - EventKuy')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-500">Kelola semua acara dan persiapan dengan mudah.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-full px-4 py-2">
            <i data-lucide="calendar" class="w-4 h-4 text-coral"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl mb-6 grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
        <div class="p-5 relative">
            <div class="absolute top-5 right-5 w-7 h-7 rounded-lg bg-coral/10 text-coral flex items-center justify-center">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalEvent }}</p>
            <p class="text-sm text-gray-500">Total Acara</p>
        </div>
        <div class="p-5 relative">
            <div class="absolute top-5 right-5 w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $eventAktif }}</p>
            <p class="text-sm text-gray-500">Acara Aktif</p>
        </div>
        <div class="p-5 relative">
            <div class="absolute top-5 right-5 w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i data-lucide="wallet" class="w-4 h-4"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500">Total Anggaran</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Upcoming Event</h3>
                <a href="{{ route('events.index') }}" class="text-xs text-coral hover:underline">Lihat Semua</a>
            </div>

            @if ($upcomingEvents->isEmpty())
                <p class="text-sm text-gray-400">Belum ada acara mendatang.</p>
            @else
                <div class="space-y-3">
                    @foreach ($upcomingEvents as $event)
                        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-3 hover:bg-gray-50 rounded-xl p-2 -mx-2">
                            <div class="w-11 h-11 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-semibold flex-shrink-0">
                                {{ strtoupper(substr($event->nama_event, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-gray-800 truncate">{{ $event->nama_event }}</p>
                                <p class="text-xs text-gray-500">{{ $event->tanggal_event->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full whitespace-nowrap">
                                H-{{ $event->hari_menuju_event }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Kalender</h3>
                <span class="text-xs text-gray-400">{{ now()->translatedFormat('F Y') }}</span>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-xs">
                @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $hari)
                    <div class="text-gray-400 font-medium py-1">{{ $hari }}</div>
                @endforeach

                @php
                    $awalBulan = now()->startOfMonth();
                    $offset = $awalBulan->dayOfWeekIso - 1;
                    $jumlahHari = now()->daysInMonth;
                    $tanggalEventBulanIni = $events->map(fn($e) => $e->tanggal_event->day . '-' . $e->tanggal_event->month)->toArray();
                @endphp

                @for ($i = 0; $i < $offset; $i++)
                    <div></div>
                @endfor

                @for ($tgl = 1; $tgl <= $jumlahHari; $tgl++)
                    @php
                        $isToday = $tgl == now()->day;
                        $hasEvent = in_array($tgl . '-' . now()->month, $tanggalEventBulanIni);
                    @endphp
                    <div class="relative py-1.5 rounded-lg {{ $isToday ? 'bg-coral text-white font-semibold' : 'text-gray-700' }}">
                        {{ $tgl }}
                        @if ($hasEvent && !$isToday)
                            <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-coral rounded-full"></span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Notifikasi</h3>
            <div class="space-y-3 text-sm">
                @if ($events->isEmpty())
                    <p class="text-gray-400">Belum ada aktivitas.</p>
                @else
                    @foreach ($events->sortByDesc('created_at')->take(3) as $event)
                        <div class="flex gap-2">
                            <i data-lucide="bell" class="w-4 h-4 text-coral mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-700">Acara <span class="font-medium">{{ $event->nama_event }}</span> dibuat</p>
                                <p class="text-xs text-gray-400">{{ $event->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6">
        <div class="flex items-center gap-2 mb-3">
            <i data-lucide="cloud-sun" class="w-5 h-5 text-blue-400"></i>
            <h3 class="font-semibold text-gray-800">Weather Alert</h3>
        </div>
        <p class="text-sm text-gray-400">Menunggu integrasi modul cek cuaca (Mhs 3). Akan menampilkan peringatan otomatis saat acara H-3.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-8">
        <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Anggaran</h3>
        <div class="flex items-center gap-8">
            <div>
                <p class="text-xs text-gray-500">Total Anggaran</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="bg-coral h-2 rounded-full" style="width: {{ $totalEvent > 0 ? 100 : 0 }}%"></div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Jumlah Event</p>
                <p class="text-lg font-bold text-gray-800">{{ $totalEvent }}</p>
            </div>
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Semua Acara</h2>
            <a href="{{ route('events.create') }}"
               class="bg-coral hover:bg-red-400 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
                + Buat Acara
            </a>
        </div>

        @if ($events->isEmpty())
            <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-500">
                Belum ada event. Yuk buat event pertamamu!
            </div>
        @else
            <div class="grid gap-3">
                @foreach ($events as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="block bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-coral/10 text-coral flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($event->nama_event, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $event->nama_event }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ ucfirst($event->jenis_event) }} &middot;
                                        {{ $event->tanggal_event->translatedFormat('d F Y') }}
                                        @if ($event->lokasi_venue)
                                            &middot; {{ $event->lokasi_venue }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if ($event->butuh_cek_cuaca)
                                <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full whitespace-nowrap">
                                    H-{{ $event->hari_menuju_event }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection