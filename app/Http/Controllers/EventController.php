<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Event;
use App\Models\Rundown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->with(['budgetItems', 'rundowns'])
            ->orderBy('tanggal_event')
            ->get();

        $totalEvent = $events->count();
        $eventAktif = $events->filter(fn ($e) => $e->hari_menuju_event >= 0)->count();
        $totalAnggaran = $events->sum(fn ($e) => $e->total_anggaran);
        $upcomingEvents = $events->filter(fn ($e) => $e->hari_menuju_event >= 0)->take(3);

        return view('events.index', compact(
            'events',
            'totalEvent',
            'eventAktif',
            'totalAnggaran',
            'upcomingEvents'
        ));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_event' => ['required', 'string', 'max:255'],
            'jenis_event' => ['required', 'in:wedding,konser,lainnya'],
            'tipe_lokasi' => ['required', 'in:indoor,outdoor'],
            'jumlah_tamu' => ['nullable', 'integer', 'min:0'],
            'harga_per_orang' => ['nullable', 'numeric', 'min:0'],
            'nama_paket' => ['nullable', 'string', 'max:255'],
            'fasilitas_paket' => ['nullable', 'string'],
            'tanggal_event' => ['required', 'date'],
            'lokasi_venue' => ['nullable', 'string', 'max:255'],
            'kota_venue' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],

            'rundown' => ['nullable', 'array'],
            'rundown.*.waktu' => ['nullable', 'required_with:rundown.*.kegiatan', 'date_format:H:i'],
            'rundown.*.kegiatan' => ['nullable', 'string', 'max:255'],
            'rundown.*.pic' => ['nullable', 'string', 'max:255'],

            'anggaran' => ['nullable', 'array'],
            'anggaran.*.nama_item' => ['nullable', 'string', 'max:255'],
            'anggaran.*.estimasi_biaya' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nama_event.required' => 'Nama event wajib diisi.',
            'jenis_event.required' => 'Jenis event wajib dipilih.',
            'tipe_lokasi.required' => 'Tipe lokasi (indoor/outdoor) wajib dipilih.',
            'tanggal_event.required' => 'Tanggal event wajib diisi.',
            'tanggal_event.date' => 'Format tanggal tidak valid.',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'nama_event' => $validated['nama_event'],
            'jenis_event' => $validated['jenis_event'],
            'tipe_lokasi' => $validated['tipe_lokasi'],
            'jumlah_tamu' => $validated['jumlah_tamu'] ?? null,
            'harga_per_orang' => $validated['harga_per_orang'] ?? null,
            'nama_paket' => $validated['nama_paket'] ?? null,
            'fasilitas_paket' => $validated['fasilitas_paket'] ?? null,
            'tanggal_event' => $validated['tanggal_event'],
            'lokasi_venue' => $validated['lokasi_venue'] ?? null,
            'kota_venue' => $validated['kota_venue'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        foreach ($request->input('rundown', []) as $baris) {
            if (!empty($baris['kegiatan'])) {
                Rundown::create([
                    'event_id' => $event->id,
                    'waktu' => $baris['waktu'],
                    'kegiatan' => $baris['kegiatan'],
                    'pic' => $baris['pic'] ?? null,
                ]);
            }
        }

        foreach ($request->input('anggaran', []) as $baris) {
            if (!empty($baris['nama_item'])) {
                BudgetItem::create([
                    'event_id' => $event->id,
                    'nama_item' => $baris['nama_item'],
                    'estimasi_biaya' => $baris['estimasi_biaya'] ?? 0,
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil dibuat!');
    }

    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load(['rundowns', 'budgetItems']);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load(['rundowns', 'budgetItems']);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'nama_event' => ['required', 'string', 'max:255'],
            'jenis_event' => ['required', 'in:wedding,konser,lainnya'],
            'tipe_lokasi' => ['required', 'in:indoor,outdoor'],
            'jumlah_tamu' => ['nullable', 'integer', 'min:0'],
            'harga_per_orang' => ['nullable', 'numeric', 'min:0'],
            'nama_paket' => ['nullable', 'string', 'max:255'],
            'fasilitas_paket' => ['nullable', 'string'],
            'tanggal_event' => ['required', 'date'],
            'lokasi_venue' => ['nullable', 'string', 'max:255'],
            'kota_venue' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],

            'rundown' => ['nullable', 'array'],
            'rundown.*.waktu' => ['nullable', 'date_format:H:i'],
            'rundown.*.kegiatan' => ['nullable', 'string', 'max:255'],
            'rundown.*.pic' => ['nullable', 'string', 'max:255'],

            'anggaran' => ['nullable', 'array'],
            'anggaran.*.nama_item' => ['nullable', 'string', 'max:255'],
            'anggaran.*.estimasi_biaya' => ['nullable', 'numeric', 'min:0'],
        ]);

        $event->update([
            'nama_event' => $validated['nama_event'],
            'jenis_event' => $validated['jenis_event'],
            'tipe_lokasi' => $validated['tipe_lokasi'],
            'jumlah_tamu' => $validated['jumlah_tamu'] ?? null,
            'harga_per_orang' => $validated['harga_per_orang'] ?? null,
            'nama_paket' => $validated['nama_paket'] ?? null,
            'fasilitas_paket' => $validated['fasilitas_paket'] ?? null,
            'tanggal_event' => $validated['tanggal_event'],
            'lokasi_venue' => $validated['lokasi_venue'] ?? null,
            'kota_venue' => $validated['kota_venue'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $event->rundowns()->delete();
        foreach ($request->input('rundown', []) as $baris) {
            if (!empty($baris['kegiatan'])) {
                Rundown::create([
                    'event_id' => $event->id,
                    'waktu' => $baris['waktu'],
                    'kegiatan' => $baris['kegiatan'],
                    'pic' => $baris['pic'] ?? null,
                ]);
            }
        }

        $event->budgetItems()->delete();
        foreach ($request->input('anggaran', []) as $baris) {
            if (!empty($baris['nama_item'])) {
                BudgetItem::create([
                    'event_id' => $event->id,
                    'nama_item' => $baris['nama_item'],
                    'estimasi_biaya' => $baris['estimasi_biaya'] ?? 0,
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}