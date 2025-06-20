<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        // The settings index no longer displays paginated lists of categories or
        // announcements. These resources have their own dedicated routes where
        // pagination is handled. Removing the queries here keeps the page light
        // and avoids unnecessary database calls.

        return view('settings.index');
    }

    private function appearanceData(): array
    {
        return [
            'primary' => setting('color_primary', '#1B2660'),
            'accent' => setting('color_accent', '#FFCD38'),
            'font_primary' => setting('font_primary', 'Poppins'),
            'font_secondary' => setting('font_secondary', 'Roboto'),
            'home_heading' => setting('home_heading', 'Welcome to the LCCD Integrated Information System'),
            'home_tagline' => setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.'),
            'header_text' => setting('header_text', 'La Consolacion College Daet'),
            'footer_text' => setting('footer_text', "Empowering Christ-centered digital transformation\n© {year} La Consolacion College Daet CMS"),
            'show_footer' => setting('show_footer', true),
            'logo' => setting('logo_path'),
            'favicon' => setting('favicon_path'),
        ];
    }

    public function editTheme()
    {
        return view('settings.appearance', $this->appearanceData());
    }

    public function updateTheme(Request $request)
    {
        $data = $request->validate([
            'color_primary' => ['required', 'regex:/^#(?:[0-9A-Fa-f]{3}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/'],
            'color_accent' => ['required', 'regex:/^#(?:[0-9A-Fa-f]{3}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/'],
            'font_primary' => 'required|in:Poppins,Roboto,Montserrat',
            'font_secondary' => 'required|in:Poppins,Roboto,Montserrat',
            'home_heading' => 'required|string',
            'home_tagline' => 'required|string',
        ]);

        \App\Models\Setting::set('color_primary', $data['color_primary']);
        \App\Models\Setting::set('color_accent', $data['color_accent']);
        \App\Models\Setting::set('font_primary', $data['font_primary']);
        \App\Models\Setting::set('font_secondary', $data['font_secondary']);
        \App\Models\Setting::set('home_heading', $data['home_heading']);
        \App\Models\Setting::set('home_tagline', $data['home_tagline']);

        return redirect()->route('settings.theme')->with('success', 'Theme updated');
    }

    public function editInstitution()
    {
        return view('settings.appearance', $this->appearanceData());
    }

    public function updateInstitution(Request $request)
    {
        $data = $request->validate([
            'header_text' => 'required|string',
            'footer_text' => 'required|string',
            'show_footer' => 'boolean',
        ]);

        \App\Models\Setting::set('header_text', $data['header_text']);
        \App\Models\Setting::set('footer_text', $data['footer_text']);
        \App\Models\Setting::set('show_footer', $request->boolean('show_footer'));

        return redirect()->route('settings.institution')->with('success', 'Institution settings updated');
    }

    public function editLocalization()
    {
        return view('settings.datetime', [
            'timezone' => setting('timezone', config('app.timezone')),
            'date_format' => setting('date_format', 'Y-m-d'),
            'timezones' => \DateTimeZone::listIdentifiers(),
        ]);
    }

    public function updateLocalization(Request $request)
    {
        $data = $request->validate([
            'timezone' => 'required|timezone',
            'date_format' => 'required|in:Y-m-d,d/m/Y',
        ]);

        \App\Models\Setting::set('timezone', $data['timezone']);
        \App\Models\Setting::set('date_format', $data['date_format']);

        config(['app.timezone' => setting('timezone')]);
        date_default_timezone_set($data['timezone']);

        return redirect()->route('settings.localization')->with('success', 'Localization settings updated');
    }

    public function editBranding()
    {
        return view('settings.appearance', $this->appearanceData());
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {

            $oldLogo = setting('logo_path');
            if ($oldLogo) {
                if (Str::startsWith($oldLogo, 'storage/')) {
                    $oldLogo = Str::replaceFirst('storage/', '', $oldLogo);
                }
                Storage::disk('public')->delete($oldLogo);
            }

            $manager = new ImageManager(new GdDriver);
            $image = $manager->read($request->file('logo')->getRealPath())->resize(300);
            $path = 'branding/'.Str::uuid().'.'.$request->file('logo')->extension();
            $encoded = $image->encodeByPath($path);
            Storage::disk('public')->put($path, $encoded->toString());
            \App\Models\Setting::set('logo_path', 'storage/'.$path);
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = setting('favicon_path');
            if ($oldFavicon) {
                if (Str::startsWith($oldFavicon, 'storage/')) {
                    $oldFavicon = Str::replaceFirst('storage/', '', $oldFavicon);
                }
                Storage::disk('public')->delete($oldFavicon);

            }
            $manager = new ImageManager(new GdDriver);
            $image = $manager->read($request->file('favicon')->getRealPath())->resize(32);
            $path = 'branding/'.Str::uuid().'.'.$request->file('favicon')->extension();
            $encoded = $image->encodeByPath($path);
            Storage::disk('public')->put($path, $encoded->toString());
            \App\Models\Setting::set('favicon_path', 'storage/'.$path);
        }

        return redirect()->route('settings.branding')->with('success', 'Branding updated');
    }

    public function editNotifications()
    {
        $placeholder = '{{ message }}';
        return view('settings.notifications', compact('placeholder'));
    }

    public function updateNotifications(Request $request)
    {
        $data = $request->validate([
            'notify_ticket_updates' => 'sometimes|boolean',
            'notify_job_order_status' => 'sometimes|boolean',
            'notify_requisition_status' => 'sometimes|boolean',
            'notify_low_stock' => 'sometimes|boolean',
            'template_ticket_updates' => 'required|string',
            'template_job_order_status' => 'required|string',
            'template_requisition_status' => 'required|string',
            'template_low_stock' => 'required|string',
        ]);

        \App\Models\Setting::set('notify_ticket_updates', $request->boolean('notify_ticket_updates'));
        \App\Models\Setting::set('notify_job_order_status', $request->boolean('notify_job_order_status'));
        \App\Models\Setting::set('notify_requisition_status', $request->boolean('notify_requisition_status'));
        \App\Models\Setting::set('notify_low_stock', $request->boolean('notify_low_stock'));

        $data['template_ticket_updates'] = ltrim($data['template_ticket_updates'], '@');
        $data['template_job_order_status'] = ltrim($data['template_job_order_status'], '@');
        $data['template_requisition_status'] = ltrim($data['template_requisition_status'], '@');
        $data['template_low_stock'] = ltrim($data['template_low_stock'], '@');

        \App\Models\Setting::set('template_ticket_updates', $data['template_ticket_updates']);
        \App\Models\Setting::set('template_job_order_status', $data['template_job_order_status']);
        \App\Models\Setting::set('template_requisition_status', $data['template_requisition_status']);
        \App\Models\Setting::set('template_low_stock', $data['template_low_stock']);

        return redirect()->route('settings.notifications')->with('success', 'Notification settings updated');
    }

    public function editSla()
    {
        return view('settings.sla', [
            'enabled' => setting('sla_enabled', true),
            'interval' => setting('sla_interval', 1),
        ]);
    }

    public function updateSla(Request $request)
    {
        $data = $request->validate([
            'sla_enabled' => 'sometimes|boolean',
            'sla_interval' => 'required|integer|min:1|max:60',
        ]);

        \App\Models\Setting::set('sla_enabled', $request->boolean('sla_enabled'));
        \App\Models\Setting::set('sla_interval', $data['sla_interval']);

        return redirect()->route('settings.sla')->with('success', 'Escalation settings updated');
    }
}
