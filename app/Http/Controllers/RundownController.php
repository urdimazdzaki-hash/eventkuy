<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class RundownController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->with('rundowns')
            ->orderBy('tanggal_event')
            ->get()
            ->filter(fn($e) => $e->rundowns->isNotEmpty());

        return view('rundowns.index', compact('events'));
    }
}