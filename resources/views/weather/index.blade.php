<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Halaman /cuaca — daftar event outdoor beserta prakiraan cuaca.
     */
    public function index()
    {
        $events = Event::where('user_id', auth()->id())
            ->where('tipe_lokasi', 'outdoor')
            ->whereDate('tanggal_event', '>=', now())
            ->whereNotNull('kota_venue')
            ->orderBy('tanggal_event')
            ->get();

        $eventsWithWeather = collect();

        foreach ($events as $event) {
            $forecast = $this->buildForecastForEvent($event);

            if ($forecast) {
                $eventsWithWeather->push([
                    'event' => $event,
                    'forecast' => $forecast,
                ]);
            }
        }

        return view('weather.index', compact('eventsWithWeather'));
    }

    /**
     * Endpoint /test-cuaca/{city} — probabilitas hujan 3 hari ke depan untuk sebuah kota.
     */
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
                'message' => 'Gagal mengambil data cuaca',
                'error' => $response->json('message', 'Unknown error'),
            ], $response->status());
        }

        $data = $response->json();
        $grouped = collect($data['list'])->groupBy(fn($item) => substr($item['dt_txt'], 0, 10));

        $result = [];
        $hariKe = 0;
        foreach ($grouped as $tanggal => $items) {
            if ($hariKe >= 3) break;
            $day = $this->summarizeDay($items);
            $day['date'] = $tanggal;
            $result[] = $day;
            $hariKe++;
        }

        return response()->json([
            'city' => $data['city']['name'],
            'forecast' => $result,
        ]);
    }

    /**
     * Ringkasan cuaca untuk satu event — dipakai di events/show.blade.php.
     */
    public function getWeatherSummaryForEvent(Event $event)
    {
        $forecast = $this->buildForecastForEvent($event);

        if (!$forecast) {
            return null;
        }

        return [
            'today' => $forecast[0],
            'next_3_days' => array_slice($forecast, 1),
        ];
    }

    /**
     * Endpoint API: GET /api/weather?city=Jakarta
     */
    public function current(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $request->query('city'),
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
            'lang' => 'id',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal mengambil data cuaca',
                'error' => $response->json('message', 'Unknown error'),
            ], $response->status());
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

    /**
     * Ambil forecast harian (hari ini + beberapa hari ke depan) untuk sebuah event.
     * Index 0 = hari event itu sendiri.
     */
    private function buildForecastForEvent(Event $event): ?array
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
        $grouped = collect($data['list'])->groupBy(fn($item) => substr($item['dt_txt'], 0, 10));

        $tanggalEvent = $event->tanggal_event->format('Y-m-d');

        if (!isset($grouped[$tanggalEvent])) {
            return null;
        }

        $forecast = [];

        $today = $this->summarizeDay($grouped[$tanggalEvent]);
        $today['date'] = $tanggalEvent;
        $forecast[] = $today;

        $count = 0;
        foreach ($grouped as $tanggal => $items) {
            if ($tanggal === $tanggalEvent) continue;
            if ($count >= 3) break;
            $day = $this->summarizeDay($items);
            $day['date'] = $tanggal;
            $forecast[] = $day;
            $count++;
        }

        return $forecast;
    }

    /**
     * Ringkas satu hari cuaca dari kumpulan data 3-jam-an OpenWeatherMap.
     */
    private function summarizeDay($items): array
    {
        $avgTemp = round($items->avg(fn($i) => $i['main']['temp']));
        $rainProb = round($items->max(fn($i) => ($i['pop'] ?? 0) * 100));
        $cuacaUtama = $items->first()['weather'][0] ?? null;

        if ($rainProb >= 80) {
            $mitigasi = [
                'level' => 'bahaya',
                'label' => 'Risiko Tinggi Hujan Lebat',
                'badge_class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                'rekomendasi' => [
                    'Sewa tenda tambahan untuk area outdoor',
                    'Siapkan pawang hujan atau alternatif indoor',
                    'Informasikan tamu untuk membawa payung',
                ],
            ];
        } elseif ($rainProb >= 40) {
            $mitigasi = [
                'level' => 'waspada',
                'label' => 'Waspada Kemungkinan Hujan',
                'badge_class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                'rekomendasi' => [
                    'Siapkan payung cadangan di lokasi',
                    'Pantau update cuaca H-1 sebelum acara',
                ],
            ];
        } else {
            $mitigasi = [
                'level' => 'aman',
                'label' => 'Cuaca Cerah',
                'badge_class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'rekomendasi' => [
                    'Kondisi ideal untuk acara outdoor',
                ],
            ];
        }

        return [
            'avg_temp' => $avgTemp,
            'description' => $cuacaUtama['description'] ?? '-',
            'icon' => $cuacaUtama['icon'] ?? '01d',
            'rain_probability' => $rainProb,
            'mitigasi' => $mitigasi,
        ];
    }
}