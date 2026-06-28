<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        if ($event->status_pembayaran === 'lunas') {
            return redirect()->route('events.show', $event);
        }

        return view('events.payment', compact('event'));
    }

    public function confirm(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'jumlah_bayar' => ['required', 'numeric', 'min:1', 'max:' . $event->sisa_pembayaran],
        ], [
            'jumlah_bayar.max' => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan.',
        ]);

        $totalDibayar = ($event->jumlah_dibayar ?? 0) + $validated['jumlah_bayar'];
        $statusBaru = $totalDibayar >= $event->total_anggaran ? 'lunas' : 'dp';

        $event->update([
            'jumlah_dibayar' => $totalDibayar,
            'status_pembayaran' => $statusBaru,
            'paid_at' => now(),
        ]);

        $pesan = $statusBaru === 'lunas'
            ? 'Pembayaran lunas! Acara sudah aktif sepenuhnya.'
            : 'Pembayaran DP berhasil dikonfirmasi. Sisa pembayaran tercatat.';

        return redirect()->route('events.show', $event)
            ->with('success', $pesan);
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}