<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', auth()->id())
            ->whereDate('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->get();

        $eventsWithWeather = $events->map(function ($event) {

            $event->hari_menuju_event = now()->diffInDays($event->tanggal_event, false);

            $forecast = collect();

            if ($event->kota_venue) {

                $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
                    'q' => $event->kota_venue,
                    'appid' => config('services.openweather.key'),
                    'units' => 'metric',
                    'lang' => 'id',
                    'cnt' => 40,
                ]);

                if ($response->successful()) {

                    $data = $response->json();

                    $grouped = collect($data['list'])->groupBy(function ($item) {
                        return substr($item['dt_txt'], 0, 10);
                    });

                    $forecast = $grouped->take(3)->map(function ($items, $tanggal) {

                        $avgTemp = round($items->avg(fn($i) => $i['main']['temp']));

                        $rainProbability = round(
                            $items->max(fn($i) => ($i['pop'] ?? 0) * 100)
                        );

                        $weather = $items->first()['weather'][0] ?? [];

                        return [
                            'date' => $tanggal,
                            'avg_temp' => $avgTemp,
                            'rain_probability' => $rainProbability,
                            'description' => $weather['description'] ?? '-',
                            'icon' => $weather['icon'] ?? '01d',
                            'mitigasi' => $this->buildMitigasi($rainProbability),
                        ];

                    })->values();
                }
            }

            return [
                'event' => $event,
                'forecast' => $forecast,
            ];
        });

        return view('weather.index', compact('eventsWithWeather'));
    }

    private function buildMitigasi($rainProbability)
    {
        if ($rainProbability >= 70) {
            return [
                'level' => 'bahaya',
                'label' => 'Bahaya',
                'badge_class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                'rekomendasi' => [
                    'Siapkan tenda',
                    'Siapkan lokasi indoor',
                    'Pertimbangkan reschedule'
                ]
            ];
        }

        if ($rainProbability >= 50) {
            return [
                'level' => 'waspada',
                'label' => 'Waspada',
                'badge_class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                'rekomendasi' => [
                    'Siapkan tenda',
                    'Pantau cuaca berkala'
                ]
            ];
        }

        return [
            'level' => 'aman',
            'label' => 'Aman',
            'badge_class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'rekomendasi' => [
                'Cuaca baik',
                'Event dapat berjalan normal'
            ]
        ];
    }

    public function getRainProbabilityNext3Days($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
            'lang' => 'id',
            'cnt' => 40,
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal mengambil data cuaca'
            ], 500);
        }

        $data = $response->json();

        $grouped = collect($data['list'])->groupBy(function ($item) {
            return substr($item['dt_txt'], 0, 10);
        });

        $forecast = $grouped->take(3)->map(function ($items, $tanggal) {

            $avgTemp = round($items->avg(fn($i) => $i['main']['temp']));
            $rainProbability = round($items->max(fn($i) => ($i['pop'] ?? 0) * 100));

            $weather = $items->first()['weather'][0] ?? [];

            return [
                'date' => $tanggal,
                'avg_temp' => $avgTemp,
                'rain_probability' => $rainProbability,
                'description' => $weather['description'] ?? '-',
                'icon' => $weather['icon'] ?? '01d',
            ];

        })->values();

        return response()->json([
            'city' => $data['city']['name'],
            'forecast' => $forecast,
        ]);
    }

    /**
     * Ringkasan cuaca untuk satu event — dipakai di events/show.blade.php.
     * Mengembalikan 'today' (avg_temp, description, icon, rain_probability, mitigasi)
     * dan 'next_3_days' (array dengan struktur yang sama + date).
     */
    public function getWeatherSummaryForEvent(Event $event)
    {
        if (!$event->kota_venue) {
            return null;
        }

        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $event->kota_venue,
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
            'lang' => 'id',
            'cnt' => 40,
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        $grouped = collect($data['list'])->groupBy(function ($item) {
            return substr($item['dt_txt'], 0, 10);
        });

        $buildDay = function ($items) {
            $avgTemp = round($items->avg(fn($i) => $i['main']['temp']));
            $rainProbability = round($items->max(fn($i) => ($i['pop'] ?? 0) * 100));
            $weather = $items->first()['weather'][0] ?? [];

            return [
                'avg_temp' => $avgTemp,
                'description' => $weather['description'] ?? '-',
                'icon' => $weather['icon'] ?? '01d',
                'rain_probability' => $rainProbability,
                'mitigasi' => $this->buildMitigasi($rainProbability),
            ];
        };

        $tanggalEvent = $event->tanggal_event->format('Y-m-d');

        if (!isset($grouped[$tanggalEvent])) {
            return null;
        }

        $today = $buildDay($grouped[$tanggalEvent]);
        $today['date'] = $tanggalEvent;

        $next3Days = [];
        $count = 0;
        foreach ($grouped as $tanggal => $items) {
            if ($tanggal === $tanggalEvent) continue;
            if ($count >= 3) break;
            $day = $buildDay($items);
            $day['date'] = $tanggal;
            $next3Days[] = $day;
            $count++;
        }

        return [
            'today' => $today,
            'next_3_days' => $next3Days,
        ];
    }

    public function current(Request $request)
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $request->city,
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
            'lang' => 'id',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal mengambil data cuaca'
            ], 500);
        }

        $data = $response->json();

        return response()->json([
            'city' => $data['name'],
            'suhu' => $data['main']['temp'],
            'terasa_seperti' => $data['main']['feels_like'],
            'kelembaban' => $data['main']['humidity'],
            'kondisi' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon'],
        ]);
    }
}