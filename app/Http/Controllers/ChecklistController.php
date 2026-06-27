<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'tugas' => ['required', 'string', 'max:255'],
        ]);

        Checklist::create([
            'event_id' => $event->id,
            'tugas' => $request->tugas,
            'selesai' => false,
        ]);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function toggle(Event $event, Checklist $checklist)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $checklist->update(['selesai' => !$checklist->selesai]);

        return back();
    }

    public function destroy(Event $event, Checklist $checklist)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $checklist->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}