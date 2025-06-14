<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $ticketCategories = \App\Models\TicketCategory::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        $jobOrderTypes = \App\Models\JobOrderType::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        $inventoryCategories = \App\Models\InventoryCategory::with('parent')
            ->paginate($perPage)
            ->withQueryString();

        $documentCategories = \App\Models\DocumentCategory::paginate($perPage)
            ->withQueryString();

        $announcements = \App\Models\Announcement::paginate($perPage)
            ->withQueryString();

        return view('settings.index', [
            'ticketCategories' => $ticketCategories,
            'jobOrderTypes' => $jobOrderTypes,
            'inventoryCategories' => $inventoryCategories,
            'documentCategories' => $documentCategories,
            'announcements' => $announcements,
        ]);
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
            'color_primary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
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
        return view('settings.institution', [
            'address' => setting('institution_address', 'Gov. Panotes Avenue, Daet, Camarines Norte 4600'),
            'phone' => setting('institution_phone', '(054) 571-3456'),
            'email' => setting('helpdesk_email', 'helpdesk@lccd.edu.ph'),
            'header_text' => setting('header_text', 'La Consolacion College Daet'),
            'footer_text' => setting('footer_text', 'Empowering Christ-centered digital transformation'),
            'show_footer' => setting('show_footer', true),
        ]);
    }

    public function updateInstitution(Request $request)
    {
        $data = $request->validate([
            'institution_address' => 'required|string',
            'institution_phone' => 'required|string',
            'helpdesk_email' => 'required|email',
            'header_text' => 'required|string',
            'footer_text' => 'required|string',
            'show_footer' => 'boolean',
        ]);

        \App\Models\Setting::set('institution_address', $data['institution_address']);
        \App\Models\Setting::set('institution_phone', $data['institution_phone']);
        \App\Models\Setting::set('helpdesk_email', $data['helpdesk_email']);
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

        return redirect()->route('settings.localization')->with('success', 'Localization settings updated');
    }

    public function editBranding()
    {
        return view('settings.branding', [
            'logo' => setting('logo_path'),
            'favicon' => setting('favicon_path'),
        ]);
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            \App\Models\Setting::set('logo_path', 'storage/' . $path);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('branding', 'public');
            \App\Models\Setting::set('favicon_path', 'storage/' . $path);
        }

        return redirect()->route('settings.branding')->with('success', 'Branding updated');
    }

    public function editNotifications()
    {
        return view('settings.notifications');
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
        \App\Models\Setting::set('template_ticket_updates', $data['template_ticket_updates']);
        \App\Models\Setting::set('template_job_order_status', $data['template_job_order_status']);
        \App\Models\Setting::set('template_requisition_status', $data['template_requisition_status']);
        \App\Models\Setting::set('template_low_stock', $data['template_low_stock']);

        return redirect()->route('settings.notifications')->with('success', 'Notification settings updated');
    }
}
