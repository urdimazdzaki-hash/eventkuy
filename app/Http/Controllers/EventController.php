<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Event;
use App\Models\Rundown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Tampilkan daftar event milik user yang sedang login.
     */
    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->orderBy('tanggal_event')
            ->get();

        return view('events.index', compact('events'));
    }

    /**
     * Tampilkan form tambah event baru.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Simpan event baru, beserta rundown dan anggaran (kalau diisi).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_event' => ['required', 'string', 'max:255'],
            'jenis_event' => ['required', 'in:wedding,konser,lainnya'],
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
            'tanggal_event.required' => 'Tanggal event wajib diisi.',
            'tanggal_event.date' => 'Format tanggal tidak valid.',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'nama_event' => $validated['nama_event'],
            'jenis_event' => $validated['jenis_event'],
            'tanggal_event' => $validated['tanggal_event'],
            'lokasi_venue' => $validated['lokasi_venue'] ?? null,
            'kota_venue' => $validated['kota_venue'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Simpan rundown (lewati baris yang kosong)
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

        // Simpan anggaran (lewati baris yang kosong)
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

    /**
     * Tampilkan detail satu event (rundown + anggaran).
     */
    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load(['rundowns', 'budgetItems']);

        return view('events.show', compact('event'));
    }

    /**
     * Tampilkan form edit event.
     */
    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load(['rundowns', 'budgetItems']);

        return view('events.edit', compact('event'));
    }

    /**
     * Update data event, rundown, dan anggaran.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'nama_event' => ['required', 'string', 'max:255'],
            'jenis_event' => ['required', 'in:wedding,konser,lainnya'],
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
            'tanggal_event' => $validated['tanggal_event'],
            'lokasi_venue' => $validated['lokasi_venue'] ?? null,
            'kota_venue' => $validated['kota_venue'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Hapus rundown & anggaran lama, ganti dengan yang baru (cara paling sederhana)
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

    /**
     * Hapus event.
     */
    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Pastikan event ini milik user yang sedang login.
     * Mencegah user A mengakses/edit event milik user B.
     */
    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}