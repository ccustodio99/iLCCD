<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function editTheme()
    {
        return view('settings.theme', [
            'primary' => setting('color_primary', '#1B2660'),
            'accent' => setting('color_accent', '#FFCD38'),
        ]);
    }

    public function updateTheme(Request $request)
    {
        $data = $request->validate([
            'color_primary' => 'required|string',
            'color_accent' => 'required|string',
        ]);

        \App\Models\Setting::set('color_primary', $data['color_primary']);
        \App\Models\Setting::set('color_accent', $data['color_accent']);

        return redirect()->route('settings.theme');
    }
}
