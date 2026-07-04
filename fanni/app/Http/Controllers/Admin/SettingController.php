<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyed();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'about_img_file', 'ecatalog_file_upload', 'company_logo_file', 'hero_bg_file']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        if ($request->hasFile('hero_bg_file')) {
            $old = Setting::get('hero_bg');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('hero_bg_file')->store('settings', 'public');
            Setting::set('hero_bg', $path);
        }

        if ($request->hasFile('company_logo_file')) {
            $old = Setting::get('company_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('company_logo_file')->store('settings', 'public');
            Setting::set('company_logo', $path);
        }

        if ($request->hasFile('about_img_file')) {
            $old = Setting::get('about_img');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('about_img_file')->store('settings', 'public');
            Setting::set('about_img', $path);
        }

        if ($request->hasFile('ecatalog_file_upload')) {
            $old = Setting::get('ecatalog_file');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('ecatalog_file_upload')->store('ecatalog', 'public');
            Setting::set('ecatalog_file', $path);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
