@extends('layouts.app')
@section('title', 'Cuaca - EventKuy')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-8">

    <div class="flex items-center justify-between mb-8 animate-fade-slide-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Cuaca & Mitigasi</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">Pantau kondisi cuaca untuk semua acara outdoor.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-full px-4 py-2">
            <i class="ph-duotone ph-cloud-sun text-base text-blue-400"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    @if ($eventsWithWeather->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-16 text-center animate-fade-slide-up">
            <i class="ph-duotone ph-cloud-slash text-5xl text-gray-200 dark:text-gray-700 mb-4 block"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">Belum ada acara outdoor</p>
            <p class="text-gray-400 dark:text-gray-600 text-xs mb-6">Tambahkan acara outdoor dengan kota venue untuk memantau cuaca</p>
            <a href="{{ route('events.create') }}"
                class="inline-flex items-center gap-1.5 bg-coral hover:bg-red-400 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
                <i class="ph-bold ph-plus text-xs"></i> Buat Acara
            </a>
        </div>
    @else

        @php
            $alertEvents = $eventsWithWeather->filter(function($item) {
                $today = $item['forecast'][0] ?? null;
                return $today && $today['rain_probability'] > 70 && $item['event']->hari_menuju_event <= 3;
            });
        @endphp

        @if($alertEvents->count() > 0)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-5 mb-6 animate-fade-slide-up flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center flex-shrink-0">
                    <i class="ph-duotone ph-warning text-xl text-red-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-red-700 dark:text-red-400 text-sm">Peringatan Cuaca!</p>
                    <p class="text-xs text-red-600 dark:text-red-500 mt-0.5">
                        {{ $alertEvents->count() }} acara dalam 3 hari ke depan berpotensi hujan tinggi. Segera siapkan mitigasi!
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($alertEvents as $w)
                            <span class="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2 py-1 rounded-lg">
                                {{ $w['event']->nama_event }} (H-{{ $w['event']->hari_menuju_event }})
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($eventsWithWeather as $item)
                @php
                    $event = $item['event'];
                    $forecast = $item['forecast'];
                    $today = $forecast[0] ?? null;
                    $level = $today['mitigasi']['level'] ?? 'aman';
                    $rainProb = $today['rain_probability'] ?? 0;
                    $isAlert = $level === 'bahaya' && $event->hari_menuju_event <= 3;
                @endphp

                <div class="bg-white dark:bg-gray-900 border {{ $isAlert ? 'border-red-300 dark:border-red-800' : 'border-gray-200 dark:border-gray-800' }} rounded-2xl overflow-hidden animate-fade-slide-up hover:shadow-md transition-all duration-300">

                    {{-- Header Card --}}
                    <div class="p-5 {{ $level === 'bahaya' ? 'bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/10' : ($level === 'waspada' ? 'bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/10' : 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/10') }}">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                @if($isAlert)
                                    <span class="text-xs bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 font-semibold px-2 py-0.5 rounded-full flex items-center gap-1 w-fit mb-1">
                                        <i class="ph-fill ph-warning-circle text-xs"></i> H-{{ $event->hari_menuju_event }} ALERT
                                    </span>
                                @endif
                                <h3 class="font-bold text-gray-800 dark:text-gray-100">{{ $event->nama_event }}</h3>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <i class="ph-fill ph-map-pin text-xs text-gray-400"></i>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $event->kota_venue }} &middot; {{ $event->tanggal_event->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            @if($today)
                                <span class="text-xs font-semibold px-3 py-1 rounded-full flex-shrink-0 {{ $today['mitigasi']['badge_class'] }}">
                                    {{ $today['mitigasi']['label'] }}
                                </span>
                            @endif
                        </div>

                        @if($today)
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ $today['avg_temp'] }}°</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize mt-0.5">{{ $today['description'] }}</p>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Potensi Hujan</p>
                                        <p class="text-xs font-bold {{ $rainProb > 70 ? 'text-red-500' : ($rainProb > 50 ? 'text-yellow-500' : 'text-green-500') }}">{{ $rainProb }}%</p>
                                    </div>
                                    <div class="bg-white/60 dark:bg-gray-800/60 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-700 {{ $rainProb > 70 ? 'bg-red-400' : ($rainProb > 50 ? 'bg-yellow-400' : 'bg-green-400') }}"
                                             style="width: {{ $rainProb }}%"></div>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-2 {{ $level === 'bahaya' ? 'text-red-600 dark:text-red-400' : ($level === 'waspada' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                        <i class="ph-duotone {{ $level === 'bahaya' ? 'ph-cloud-rain' : ($level === 'waspada' ? 'ph-cloud' : 'ph-sun') }} text-base"></i>
                                        <p class="text-xs font-medium">
                                            @foreach($today['mitigasi']['rekomendasi'] as $r)
                                                {{ $r }}@if(!$loop->last), @endif
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Data cuaca tidak tersedia. Pastikan kota venue sudah diisi.</p>
                        @endif
                    </div>

                    {{-- Forecast 3 Hari --}}
                    @if(count($forecast) > 0)
                        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wide font-semibold mb-3">Prakiraan 3 Hari</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($forecast as $day)
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 text-center">
                                        <p class="text-[10px] text-gray-400 mb-1">{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d M') }}</p>
                                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $day['avg_temp'] }}°</p>
                                        <p class="text-[10px] text-gray-500 capitalize mb-2">{{ $day['description'] }}</p>
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-1 mb-1 overflow-hidden">
                                            <div class="h-1 rounded-full {{ $day['rain_probability'] > 70 ? 'bg-red-400' : ($day['rain_probability'] > 50 ? 'bg-yellow-400' : 'bg-green-400') }}"
                                                 style="width: {{ $day['rain_probability'] }}%"></div>
                                        </div>
                                        <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full {{ $day['mitigasi']['badge_class'] }}">
                                            {{ $day['rain_probability'] }}%
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <p class="text-[10px] text-gray-400">Data: OpenWeatherMap</p>
                        <a href="{{ route('events.show', $event) }}"
                            class="text-xs text-coral hover:underline flex items-center gap-1">
                            Lihat Event <i class="ph-bold ph-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection