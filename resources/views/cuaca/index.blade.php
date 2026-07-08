@extends('layouts.app')
@section('title', 'Cuaca - EventKuy')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Cuaca & Mitigasi</h1>
        <p class="text-gray-500 dark:text-gray-400">Pantau kondisi cuaca untuk semua acara outdoor.</p>
    </div>

    @if ($eventsWithWeather->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-10 text-center text-gray-500">
            Belum ada acara outdoor yang perlu dipantau cuacanya.
        </div>
    @else
        <div class="grid gap-4">
            @foreach ($eventsWithWeather as $item)
                @php
                    $event = $item['event'];
                    $forecast = $item['forecast'];
                    $today = $forecast[0] ?? null;
                @endphp

                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $event->nama_event }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $event->kota_venue }} &middot; H-{{ $event->hari_menuju_event }}
                            </p>
                        </div>
                        @if ($today)
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $today['mitigasi']['badge_class'] }}">
                                {{ $today['mitigasi']['label'] }}
                            </span>
                        @endif
                    </div>

                    @if ($today)
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($forecast as $day)
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-center">
                                    <p class="text-xs text-gray-400 mb-1">{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d M') }}</p>
                                    <p class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $day['avg_temp'] }}°C</p>
                                    <p class="text-xs text-gray-500 capitalize mb-2">{{ $day['description'] }}</p>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $day['mitigasi']['badge_class'] }}">
                                        Hujan {{ $day['rain_probability'] }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <p class="text-xs text-gray-400 mb-2">Rekomendasi Mitigasi:</p>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                @foreach ($today['mitigasi']['rekomendasi'] as $rekomendasi)
                                    <li>&bull; {{ $rekomendasi }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Data cuaca tidak tersedia. Pastikan kota venue sudah diisi.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection