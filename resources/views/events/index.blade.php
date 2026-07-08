@extends('layouts.app')
@section('title', 'Dashboard - EventKuy')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-8">

    <div class="flex items-center justify-between mb-8 animate-fade-slide-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400">Kelola semua acara dan persiapan dengan mudah.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-full px-4 py-2">
            <i class="ph-duotone ph-calendar text-base text-coral"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    @php $eventTerdekat = $upcomingEvents->first(); @endphp
    @if ($eventTerdekat)
        <div class="relative rounded-2xl p-6 mb-6 overflow-hidden animate-fade-slide-up delay-100">
            <div class="absolute inset-0">
                <img src="{{ asset('images/wedding-banner.jpg') }}" alt="Wedding banner" class="w-full h-full object-cover" style="object-position: 50% 30%;">
                <div class="absolute inset-0 bg-gradient-to-r from-navy/80 to-navy/40"></div>
            </div>
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex items-center gap-2 mb-1">
                    <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Acara Terdekat</p>
                    <span class="text-white/70 text-xs">&middot; H-{{ $eventTerdekat->hari_menuju_event }}</span>
                </div>
                <h2 class="text-white text-xl font-bold mb-1">{{ $eventTerdekat->nama_event }}</h2>
                <p class="text-white/70 text-sm mb-5">
                    <i class="ph-fill ph-map-pin text-sm inline mr-1"></i>
                    {{ $eventTerdekat->lokasi_venue ?? 'Lokasi belum diset' }} &middot; {{ $eventTerdekat->tanggal_event->translatedFormat('d F Y') }}
                </p>
                <div class="flex gap-3 justify-center">
                    <div class="bg-white/20 rounded-xl px-4 py-3 text-center min-w-[64px]">
                        <p id="countdown-hari" class="text-2xl font-bold text-white">--</p>
                        <p class="text-white/70 text-xs">Hari</p>
                    </div>
                    <div class="bg-white/20 rounded-xl px-4 py-3 text-center min-w-[64px]">
                        <p id="countdown-jam" class="text-2xl font-bold text-white">--</p>
                        <p class="text-white/70 text-xs">Jam</p>
                    </div>
                    <div class="bg-white/20 rounded-xl px-4 py-3 text-center min-w-[64px]">
                        <p id="countdown-menit" class="text-2xl font-bold text-white">--</p>
                        <p class="text-white/70 text-xs">Menit</p>
                    </div>
                    <div class="bg-white/20 rounded-xl px-4 py-3 text-center min-w-[64px]">
                        <p id="countdown-detik" class="text-2xl font-bold text-white">--</p>
                        <p class="text-white/70 text-xs">Detik</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const targetDate = new Date("{{ $eventTerdekat->tanggal_event->format('Y-m-d') }}T00:00:00");
            function updateCountdown() {
                const now = new Date();
                const diff = targetDate - now;
                if (diff <= 0) {
                    document.getElementById('countdown-hari').textContent = '0';
                    document.getElementById('countdown-jam').textContent = '0';
                    document.getElementById('countdown-menit').textContent = '0';
                    document.getElementById('countdown-detik').textContent = '0';
                    return;
                }
                const hari = Math.floor(diff / (1000 * 60 * 60 * 24));
                const jam = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const menit = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const detik = Math.floor((diff % (1000 * 60)) / 1000);
                document.getElementById('countdown-hari').textContent = hari;
                document.getElementById('countdown-jam').textContent = String(jam).padStart(2, '0');
                document.getElementById('countdown-menit').textContent = String(menit).padStart(2, '0');
                document.getElementById('countdown-detik').textContent = String(detik).padStart(2, '0');
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        </script>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4 animate-fade-slide-up delay-200">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 relative overflow-hidden">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Total Acara</p>
            <p id="stat-total-event" class="text-3xl font-bold text-gray-800 dark:text-gray-100">0</p>
            <div class="absolute bottom-4 right-4 w-8 h-8 rounded-xl bg-coral/10 flex items-center justify-center">
                <i class="ph-duotone ph-calendar-check text-base text-coral"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 relative overflow-hidden">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Acara Aktif</p>
            <p id="stat-event-aktif" class="text-3xl font-bold text-gray-800 dark:text-gray-100">0</p>
            <div class="absolute bottom-4 right-4 w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="ph-duotone ph-chart-line-up text-base text-emerald-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 relative overflow-hidden">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Total Anggaran</p>
            <p id="stat-anggaran" class="text-xl font-bold text-gray-800 dark:text-gray-100 pt-1">Rp 0</p>
            <div class="absolute bottom-4 right-4 w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="ph-duotone ph-money-wavy text-base text-blue-500"></i>
            </div>
        </div>
    </div>

    {{-- Mid Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4 animate-fade-slide-up delay-300">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Upcoming Event</h3>
                <a href="{{ route('events.index') }}" class="text-xs text-coral hover:underline">Lihat Semua</a>
            </div>
            @if ($upcomingEvents->isEmpty())
                <div class="flex flex-col items-center py-6 text-center">
                    <i class="ph-duotone ph-calendar-x text-3xl text-gray-200 mb-2"></i>
                    <p class="text-xs text-gray-400">Belum ada acara mendatang</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($upcomingEvents as $event)
                        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl p-2 -mx-2 transition">
                            <div class="w-9 h-9 rounded-xl bg-coral/10 text-coral text-sm font-bold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($event->nama_event, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $event->nama_event }}</p>
                                <p class="text-xs text-gray-400">{{ $event->tanggal_event->translatedFormat('d M Y') }}</p>
                            </div>
                            @if ($event->hari_menuju_event < 0)
                                <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 font-medium px-2 py-1 rounded-lg whitespace-nowrap">Selesai</span>
                            @elseif ($event->hari_menuju_event == 0)
                                <span class="text-xs bg-coral/10 text-coral font-medium px-2 py-1 rounded-lg whitespace-nowrap animate-pulse">Berlangsung</span>
                            @else
                                <span class="text-xs bg-green-50 text-green-600 font-medium px-2 py-1 rounded-lg whitespace-nowrap">H-{{ $event->hari_menuju_event }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Kalender</h3>
                <span class="text-xs text-gray-400">{{ now()->translatedFormat('F Y') }}</span>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center">
                @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $hari)
                    <div class="text-[10px] text-gray-400 font-medium py-1">{{ $hari }}</div>
                @endforeach
                @php
                    $awalBulan = now()->startOfMonth();
                    $offset = $awalBulan->dayOfWeekIso - 1;
                    $jumlahHari = now()->daysInMonth;
                    $tanggalEventBulanIni = $events->map(fn($e) => $e->tanggal_event->day . '-' . $e->tanggal_event->month)->toArray();
                @endphp
                @for ($i = 0; $i < $offset; $i++)<div></div>@endfor
                @for ($tgl = 1; $tgl <= $jumlahHari; $tgl++)
                    @php $isToday = $tgl == now()->day; $hasEvent = in_array($tgl . '-' . now()->month, $tanggalEventBulanIni); @endphp
                    <div class="relative text-[11px] py-1.5 mx-0.5 my-0.5 rounded-lg {{ $isToday ? 'bg-coral text-white font-bold' : ($hasEvent ? 'bg-coral/10 text-coral font-semibold' : 'text-gray-600 dark:text-gray-300') }}">
                        {{ $tgl }}
                        @if ($hasEvent && !$isToday)
                            <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-coral rounded-full"></span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Notifikasi</h3>
            @if ($events->isEmpty())
                <div class="flex flex-col items-center py-6 text-center">
                    <i class="ph-duotone ph-bell-slash text-3xl text-gray-200 mb-2"></i>
                    <p class="text-xs text-gray-400">Belum ada aktivitas</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($events->sortByDesc('created_at')->take(4) as $event)
                        <div class="flex gap-3 items-start">
                            <div class="w-7 h-7 rounded-lg bg-coral/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph-duotone ph-bell text-sm text-coral"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-700 dark:text-gray-300">Acara <span class="font-semibold">{{ $event->nama_event }}</span> dibuat</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $event->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Weather + Grafik --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-4 animate-fade-slide-up delay-400">

        @php
            $weatherController = new \App\Http\Controllers\WeatherController();
            $eventsButuhCekCuaca = \App\Models\Event::where('tipe_lokasi', 'outdoor')->get()->filter(fn($e) => $e->butuh_cek_cuaca);
        @endphp

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="ph-duotone ph-cloud-sun text-xl text-blue-400"></i>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Weather Alert</h3>
            </div>
            @if ($eventsButuhCekCuaca->isEmpty())
                <p class="text-xs text-gray-400">Tidak ada event yang perlu dipantau saat ini.</p>
            @else
                <div class="space-y-3">
                    @foreach ($eventsButuhCekCuaca as $ev)
                        @php $wd = $weatherController->getWeatherSummaryForEvent($ev); @endphp
                        @if ($wd)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $ev->nama_event }}</p>
                                    <p class="text-xs text-gray-400">H-{{ $ev->hari_menuju_event }} · {{ $ev->kota_venue }}</p>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $wd['today']['mitigasi']['badge_class'] }}">
                                    {{ $wd['today']['mitigasi']['label'] }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Grafik Anggaran</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total: Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</p>
                </div>
                <span class="text-xs bg-coral/10 text-coral px-2 py-1 rounded-lg font-medium">{{ $totalEvent }} acara</span>
            </div>
            @if($events->isEmpty())
                <div class="flex flex-col items-center py-4 text-center">
                    <i class="ph-duotone ph-chart-line text-3xl text-gray-200 mb-2"></i>
                    <p class="text-xs text-gray-400">Belum ada data anggaran</p>
                </div>
            @else
                <div class="overflow-x-auto cursor-grab select-none" id="chart-scroll" style="-webkit-overflow-scrolling: touch;">
                    <div style="min-width: {{ max(300, $events->count() * 80) }}px; height: 120px;">
                        <canvas id="anggaranChart"></canvas>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-2 overflow-x-auto pb-1">
                    @foreach($events as $event)
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <div class="w-1.5 h-1.5 rounded-full bg-coral"></div>
                            <p class="text-[10px] text-gray-400 whitespace-nowrap">{{ Str::limit($event->nama_event, 10) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Semua Acara --}}
    <div class="animate-fade-slide-up delay-500">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Semua Acara</p>
            <a href="{{ route('events.create') }}"
               class="flex items-center gap-1.5 bg-coral hover:opacity-90 text-white text-xs font-semibold px-4 py-2 rounded-full transition">
                <i class="ph-bold ph-plus text-xs"></i> Buat Acara
            </a>
        </div>

        @if ($events->isEmpty())
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-10 text-center">
                <i class="ph-duotone ph-calendar-x text-4xl text-gray-200 mx-auto mb-3"></i>
                <p class="text-sm text-gray-400">Belum ada event. Yuk buat event pertamamu!</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden">
                <div class="grid grid-cols-12 px-5 py-2.5 bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <div class="col-span-5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Acara</div>
                    <div class="col-span-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Jenis</div>
                    <div class="col-span-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Tanggal</div>
                    <div class="col-span-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wide text-right">Status</div>
                </div>
                @foreach ($events as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="grid grid-cols-12 px-5 py-3.5 border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition items-center last:border-0">
                        <div class="col-span-5 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-coral/10 text-coral text-xs font-bold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($event->nama_event, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $event->nama_event }}</p>
                                @if ($event->lokasi_venue)
                                    <p class="text-xs text-gray-400 truncate">{{ $event->lokasi_venue }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-lg">{{ ucfirst($event->jenis_event) }}</span>
                        </div>
                        <div class="col-span-3 text-xs text-gray-500 dark:text-gray-400">
                            {{ $event->tanggal_event->translatedFormat('d M Y') }}
                        </div>
                        <div class="col-span-2 flex items-center justify-end gap-1">
                            @if ($event->status_pembayaran === 'lunas')
                                <span class="text-xs bg-green-50 text-green-600 font-medium px-2 py-1 rounded-lg">Lunas</span>
                            @elseif ($event->status_pembayaran === 'dp')
                                <span class="text-xs bg-blue-50 text-blue-600 font-medium px-2 py-1 rounded-lg">DP</span>
                            @else
                                <span class="text-xs bg-orange-50 text-orange-600 font-medium px-2 py-1 rounded-lg">Belum Bayar</span>
                            @endif
                            @if ($event->hari_menuju_event < 0)
                                <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 font-medium px-2 py-1 rounded-lg">Selesai</span>
                            @elseif ($event->hari_menuju_event == 0)
                                <span class="text-xs bg-coral/10 text-coral font-medium px-2 py-1 rounded-lg animate-pulse">Berlangsung</span>
                            @elseif ($event->butuh_cek_cuaca)
                                <span class="text-xs bg-yellow-50 text-yellow-600 font-medium px-2 py-1 rounded-lg">H-{{ $event->hari_menuju_event }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
function animateCount(id, target, duration, isRupiah) {
    const el = document.getElementById(id);
    if (!el || target === 0) { if (el) el.textContent = isRupiah ? 'Rp 0' : '0'; return; }
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
        start += step;
        if (start >= target) { start = target; clearInterval(timer); }
        el.textContent = isRupiah ? 'Rp ' + Math.floor(start).toLocaleString('id-ID') : Math.floor(start);
    }, 16);
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        animateCount('stat-total-event', {{ $totalEvent }}, 1000, false);
        animateCount('stat-event-aktif', {{ $eventAktif }}, 1000, false);
        animateCount('stat-anggaran', {{ $totalAnggaran }}, 1500, true);
    }, 400);

    const canvas = document.getElementById('anggaranChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const isDark = document.getElementById('html-root').classList.contains('dark');
        const gradient = ctx.createLinearGradient(0, 0, 0, 120);
        gradient.addColorStop(0, 'rgba(201, 168, 76, 0.35)');
        gradient.addColorStop(1, 'rgba(201, 168, 76, 0.02)');
        const labels = @json($events->pluck('nama_event')->map(fn($n) => Str::limit($n, 10)));
        const data = @json($events->pluck('total_anggaran'));
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Anggaran',
                    data: data,
                    borderColor: '#C9A84C',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#C9A84C',
                    pointBorderColor: isDark ? '#111827' : '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (ctx) => 'Rp ' + ctx.raw.toLocaleString('id-ID') },
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        padding: 8,
                        cornerRadius: 8,
                    }
                },
                scales: { x: { display: false }, y: { display: false } }
            }
        });
    }

    const chartScroll = document.getElementById('chart-scroll');
    if (chartScroll) {
        let isDown = false, startX, scrollLeft;
        chartScroll.addEventListener('mousedown', (e) => { isDown = true; chartScroll.style.cursor = 'grabbing'; startX = e.pageX - chartScroll.offsetLeft; scrollLeft = chartScroll.scrollLeft; });
        chartScroll.addEventListener('mouseleave', () => { isDown = false; chartScroll.style.cursor = 'grab'; });
        chartScroll.addEventListener('mouseup', () => { isDown = false; chartScroll.style.cursor = 'grab'; });
        chartScroll.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            chartScroll.scrollLeft = scrollLeft - (e.pageX - chartScroll.offsetLeft - startX) * 1.5;
        });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection