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
            'font_primary' => setting('font_primary', 'Poppins'),
            'font_secondary' => setting('font_secondary', 'Roboto'),
            'home_heading' => setting('home_heading', 'Welcome to the LCCD Integrated Information System (CMS)'),
            'home_tagline' => setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.'),
        ]);
    }

    public function updateTheme(Request $request)
    {
        $data = $request->validate([
            'color_primary' => 'required|string',
            'color_accent' => 'required|string',
            'font_primary' => 'required|string',
            'font_secondary' => 'required|string',
            'home_heading' => 'required|string',
            'home_tagline' => 'required|string',
        ]);

        \App\Models\Setting::set('color_primary', $data['color_primary']);
        \App\Models\Setting::set('color_accent', $data['color_accent']);
        \App\Models\Setting::set('font_primary', $data['font_primary']);
        \App\Models\Setting::set('font_secondary', $data['font_secondary']);
        \App\Models\Setting::set('home_heading', $data['home_heading']);
        \App\Models\Setting::set('home_tagline', $data['home_tagline']);

        return redirect()->route('settings.theme');
    }
}
