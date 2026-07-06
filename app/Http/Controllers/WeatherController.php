<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getCurrentWeather(string $city): ?array
    {
        $cacheKey = 'weather_current_' . strtolower($city);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($city) {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
                'lang' => 'id',
            ]);

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        });
    }

    public function getForecast(string $city): ?array
    {
        $cacheKey = 'weather_forecast_' . strtolower($city);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($city) {
            $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
                'q' => $city,
                'appid' => config('services.openweather.key'),
                'units' => 'metric',
                'lang' => 'id',
            ]);

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        });
    }

    public function getRainProbabilityNext3Days(string $city): array
    {
        $forecast = $this->getForecast($city);

        if (!$forecast || !isset($forecast['list'])) {
            return [];
        }

        $dailyData = [];

        foreach ($forecast['list'] as $item) {
            $date = date('Y-m-d', $item['dt']);
            $pop = ($item['pop'] ?? 0) * 100;

            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'date' => $date,
                    'pop_values' => [],
                    'temp_values' => [],
                    'max_pop_so_far' => -1,
                    'description_at_max_pop' => '',
                ];
            }

            $dailyData[$date]['pop_values'][] = $pop;
            $dailyData[$date]['temp_values'][] = $item['main']['temp'] ?? 0;

            if ($pop > $dailyData[$date]['max_pop_so_far']) {
                $dailyData[$date]['max_pop_so_far'] = $pop;
                $dailyData[$date]['description_at_max_pop'] = $item['weather'][0]['description'] ?? '';
            }
        }

        $result = [];
        $count = 0;

        foreach ($dailyData as $date => $data) {
            if ($count >= 3) break;

            $maxPop = max($data['pop_values']);
            $avgTemp = round(array_sum($data['temp_values']) / count($data['temp_values']), 1);
            $mainDescription = $data['description_at_max_pop'] ?: '-';

            $result[] = [
                'date' => $date,
                'rain_probability' => round($maxPop),
                'avg_temp' => $avgTemp,
                'description' => $mainDescription,
                'mitigasi' => $this->getMitigasiRecommendation($maxPop),
            ];

            $count++;
        }

        return $result;
    }

    public function getMitigasiRecommendation(float $rainProbability): array
    {
        if ($rainProbability > 70) {
            return [
                'level' => 'bahaya',
                'label' => 'Waspada Tinggi',
                'badge_class' => 'bg-red-100 text-red-700',
                'rekomendasi' => [
                    'Pertimbangkan relokasi indoor',
                    'Hubungi pawang hujan',
                ],
            ];
        }

        if ($rainProbability > 50) {
            return [
                'level' => 'waspada',
                'label' => 'Waspada',
                'badge_class' => 'bg-yellow-100 text-yellow-700',
                'rekomendasi' => [
                    'Siapkan tenda cadangan',
                ],
            ];
        }

        return [
            'level' => 'aman',
            'label' => 'Kondisi Baik',
            'badge_class' => 'bg-green-100 text-green-700',
            'rekomendasi' => [
                'Kondisi cuaca mendukung, tidak perlu tindakan khusus',
            ],
        ];
    }

    public function index()
    {
        $events = Event::where('tipe_lokasi', 'outdoor')->get();

        $eventsWithWeather = $events->map(function ($event) {
            $forecast3Days = $this->getRainProbabilityNext3Days($event->kota_venue);

            return [
                'event' => $event,
                'forecast' => $forecast3Days,
            ];
        });

        return view('cuaca.index', ['eventsWithWeather' => $eventsWithWeather]);
    }

    public function getWeatherSummaryForEvent(Event $event): ?array
    {
        if (!$event->kota_venue) {
            return null;
        }

        $forecast3Days = $this->getRainProbabilityNext3Days($event->kota_venue);

        if (empty($forecast3Days)) {
            return null;
        }

        $hariKe = max(0, $event->hari_menuju_event);
        $todayForecast = $forecast3Days[$hariKe] ?? $forecast3Days[0];

        return [
            'today' => $todayForecast,
            'next_3_days' => $forecast3Days,
        ];
    }
}