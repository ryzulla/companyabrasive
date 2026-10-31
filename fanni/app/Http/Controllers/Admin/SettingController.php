<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /** field upload => [kunci setting, folder penyimpanan] */
    private const FILES = [
        'hero_bg_file'         => ['hero_bg', 'settings'],
        'company_logo_file'    => ['company_logo', 'settings'],
        'about_img_file'       => ['about_img', 'settings'],
        'favicon_file'         => ['favicon', 'settings'],
        'seo_og_image_file'    => ['seo_og_image', 'settings'],
        'ecatalog_file_upload' => ['ecatalog_file', 'ecatalog'],
    ];

    public function index()
    {
        $settings = Setting::allKeyed();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $url = ['nullable', 'url:http,https', 'max:255'];

        $request->validate([
            'seo_title'       => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:200'],
            'seo_keywords'    => ['nullable', 'string', 'max:255'],
            'google_analytics_id'      => ['nullable', 'regex:/^(G|GT|UA|AW)-[A-Za-z0-9-]+$/'],
            'google_site_verification' => ['nullable', 'regex:/^[A-Za-z0-9_-]+$/', 'max:100'],
            'bing_site_verification'   => ['nullable', 'regex:/^[A-Za-z0-9_-]+$/', 'max:100'],
            'social_facebook'  => $url, 'social_instagram' => $url, 'social_linkedin' => $url,
            'social_youtube'   => $url, 'social_tiktok'    => $url, 'social_twitter'  => $url,
            'email'            => ['nullable', 'email', 'max:255'],
            'maps_lat'         => ['nullable', 'numeric', 'between:-90,90'],
            'maps_lng'         => ['nullable', 'numeric', 'between:-180,180'],
            'wa_number'        => ['nullable', 'regex:/^\+?[0-9]{8,16}$/'],
            'favicon_file'      => ['nullable', 'file', 'mimes:png,ico,svg,webp,jpg,jpeg', 'max:1024'],
            'seo_og_image_file' => ['nullable', 'image', 'max:2048'],
        ], [
            'google_analytics_id.regex'      => 'ID Google Analytics tidak valid. Contoh: G-XXXXXXXXXX.',
            'google_site_verification.regex' => 'Kode verifikasi Google hanya boleh berisi huruf, angka, - dan _.',
            'bing_site_verification.regex'   => 'Kode verifikasi Bing hanya boleh berisi huruf, angka, - dan _.',
            'wa_number.regex'                => 'Nomor WhatsApp harus berupa angka, format 628xxx.',
        ]);

        $data = $request->except(array_merge(['_token', '_method', 'remove_favicon', 'remove_seo_og_image', 'maps_embed'], array_keys(self::FILES)));

        foreach ($data as $key => $value) {
            Setting::set($key, is_string($value) ? trim($value) : ($value ?? ''));
        }

        // Peta Google dibuat dari titik koordinat yang dipilih di peta (tanpa API key)
        $lat = trim((string) $request->input('maps_lat'));
        $lng = trim((string) $request->input('maps_lng'));
        Setting::set('maps_embed', ($lat !== '' && $lng !== '')
            ? "https://www.google.com/maps?q={$lat},{$lng}&z=17&output=embed"
            : '');

        foreach (self::FILES as $field => [$key, $folder]) {
            if ($request->hasFile($field)) {
                $old = Setting::get($key);
                if ($old) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set($key, $request->file($field)->store($folder, 'public'));
            }
        }

        // Hapus gambar tertentu bila diminta (centang "Hapus")
        foreach (['favicon', 'seo_og_image'] as $key) {
            if ($request->boolean('remove_' . $key) && ! $request->hasFile($key . '_file')) {
                $old = Setting::get($key);
                if ($old) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set($key, '');
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
