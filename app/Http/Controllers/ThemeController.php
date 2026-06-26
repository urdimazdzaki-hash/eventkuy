<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $current = session('theme', 'light');
        $new = $current === 'dark' ? 'light' : 'dark';

        $request->session()->put('theme', $new);

        return back();
    }
}