@extends('layouts.app')
@section('title', $event->nama_event . ' - EventKuy')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-8">

    <div class="relative rounded-2xl p-8 mb-6 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/wedding-banner.jpg') }}" alt="Wedding banner" class="w-full h-full object-cover" style="object-position: 50% 30%;">
            <div class="absolute inset-0 bg-gradient-to-r from-navy/80 to-navy/50"></div>
        </div>
        <div class="relative z-10">
            <a href="{{ route('events.index') }}" class="text-white/70 hover:text-white text-sm mb-4 inline-block">&larr; Kembali ke Dashboard</a>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $event->nama_event }}</h1>
                    <div class="flex flex-wrap gap-3 text-white/80 text-sm">
                        <span class="flex items-center gap-1">
                            <i class="ph ph-tag text-sm"></i>
                            {{ ucfirst($event->jenis_event) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ph ph-map-pin text-sm"></i>
                            {{ $event->lokasi_venue ?? 'Venue belum diset' }}{{ $event->kota_venue ? ', ' . $event->kota_venue : '' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ph ph-calendar text-sm"></i>
                            {{ $event->tanggal_event->translatedFormat('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ph ph-clock text-sm"></i>
                            @if ($event->hari_menuju_event < 0)
                                Selesai
                            @elseif ($event->hari_menuju_event == 0)
                                Berlangsung
                            @else
                                H-{{ $event->hari_menuju_event }}
                            @endif
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
        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Progress Persiapan</h2>
        <div class="flex items-center gap-8">
            <div class="relative flex-shrink-0">
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#F3F4F6" stroke-width="10"/>
                    <circle id="progress-ring" cx="60" cy="60" r="50" fill="none"
                        stroke="#C9A84C" stroke-width="10"
                        stroke-linecap="round"
                        stroke-dasharray="314"
                        stroke-dashoffset="314"
                        transform="rotate(-90 60 60)"
                        style="transition: stroke-dashoffset 1.2s ease;"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="progress-text" class="text-2xl font-bold text-coral">0%</span>
                    <span class="text-xs text-gray-400">selesai</span>
                </div>
            </div>
            <div class="flex-1 grid grid-cols-2 gap-2">
                @foreach ($checklist as $item)
                    <div class="flex items-center gap-2 text-sm {{ $item['done'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}">
                        @if ($item['done'])
                            <span class="text-coral">✓</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">○</span>
                        @endif
                        {{ $item['label'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if ($event->butuh_cek_cuaca)
        @php
            $weatherController = new \App\Http\Controllers\WeatherController();
            $weatherData = $weatherController->getWeatherSummaryForEvent($event);
        @endphp

        @if ($weatherData)
            @php $today = $weatherData['today']; @endphp
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i class="ph-duotone ph-cloud-rain text-xl text-yellow-600"></i>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Peringatan Cuaca: H-{{ $event->hari_menuju_event }}</h3>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $today['avg_temp'] }}°C</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $today['description'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Probabilitas Hujan</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $today['rain_probability'] }}%</p>
                    </div>
                </div>
                <div class="{{ $today['mitigasi']['badge_class'] }} rounded-xl p-4">
                    <p class="font-semibold mb-2">{{ $today['mitigasi']['label'] }}</p>
                    <ul class="text-sm space-y-1">
                        @foreach ($today['mitigasi']['rekomendasi'] as $rekomendasi)
                            <li>• {{ $rekomendasi }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <p class="text-xs text-gray-400 mb-2">Forecast 3 Hari Ke Depan:</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($weatherData['next_3_days'] as $day)
                            <div class="text-center p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($day['date'])->format('d M') }}</p>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $day['avg_temp'] }}°C</p>
                                <p class="text-xs {{ $day['mitigasi']['badge_class'] }} rounded px-1 mt-1">{{ $day['rain_probability'] }}%</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-5 mb-6">
                <p class="text-sm text-yellow-700 dark:text-yellow-500">
                    Acara outdoor ini sudah mendekati H-3, tapi data cuaca belum tersedia. Pastikan kota venue sudah diisi dengan benar.
                </p>
            </div>
        @endif
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
        <div class="bg-coral/5 dark:bg-gray-900 border border-coral/20 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-1">Fasilitas Paket</h2>
            <p class="text-sm text-coral font-medium mb-3">✨ Paket {{ ucfirst($event->nama_paket) }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                @foreach ($event->daftar_fasilitas as $fasilitas)
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <span class="text-coral">✓</span>
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
                    class="bg-coral hover:opacity-90 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
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
                                    {{ $item->selesai ? 'bg-coral border-coral text-white' : 'border-gray-300 dark:border-gray-600' }}">
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
                        <span class="text-sm font-medium text-coral w-16">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ $item->kegiatan }}</span>
                        @if ($item->pic)
                            <span class="text-xs bg-coral/10 text-coral px-2 py-1 rounded-full">{{ $item->pic }}</span>
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

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Status Pembayaran</h2>
            @php
                $badgeClass = match($event->status_pembayaran) {
                    'lunas' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'dp' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    default => 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                };
                $badgeLabel = match($event->status_pembayaran) {
                    'lunas' => 'Lunas',
                    'dp' => 'DP',
                    default => 'Belum Bayar',
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>

        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 mb-4">
            <div class="bg-coral h-2.5 rounded-full transition-all" style="width: {{ $event->persen_terbayar }}%"></div>
        </div>

        <div class="grid grid-cols-3 gap-4 text-sm mb-4">
            <div>
                <p class="text-gray-400">Total Anggaran</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">Rp {{ number_format($event->total_anggaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Sudah Dibayar</p>
                <p class="font-semibold text-green-600">Rp {{ number_format($event->jumlah_dibayar ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-400">Sisa Pembayaran</p>
                <p class="font-semibold text-coral">Rp {{ number_format($event->sisa_pembayaran, 0, ',', '.') }}</p>
            </div>
        </div>

        @if ($event->status_pembayaran !== 'lunas')
            <a href="{{ route('payment.show', $event) }}"
               class="inline-block bg-coral hover:opacity-90 text-white text-sm font-semibold px-5 py-2 rounded-xl transition">
                + Tambah Pembayaran
            </a>
        @else
            <p class="text-sm text-green-600 font-medium">✓ Pembayaran lunas pada {{ $event->paid_at ? \Carbon\Carbon::parse($event->paid_at)->translatedFormat('d F Y') : '-' }}</p>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const target = {{ $persen }};
    const circle = document.getElementById('progress-ring');
    const text = document.getElementById('progress-text');
    const circumference = 314;
    let current = 0;
    const duration = 1200;
    const steps = 60;
    const increment = target / steps;
    const intervalTime = duration / steps;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) { current = target; clearInterval(timer); }
        const offset = circumference - (current / 100) * circumference;
        circle.style.strokeDashoffset = offset;
        text.textContent = Math.round(current) + '%';
    }, intervalTime);
});
</script>

@endsection