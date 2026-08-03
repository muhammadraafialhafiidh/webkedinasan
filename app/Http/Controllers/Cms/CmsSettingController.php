<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'nama_website' => Setting::get('nama_website'),
            'tagline' => Setting::get('tagline'),
            'deskripsi' => Setting::get('deskripsi'),
            'email' => Setting::get('email'),
            'telepon' => Setting::get('telepon'),
            'fax' => Setting::get('fax'),
            'alamat' => Setting::get('alamat'),
            'jam_operasional' => Setting::get('jam_operasional'),
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'facebook_url' => Setting::get('facebook_url'),
            'instagram_url' => Setting::get('instagram_url'),
            'youtube_url' => Setting::get('youtube_url'),
            'twitter_url' => Setting::get('twitter_url'),
            'teks_footer' => Setting::get('teks_footer'),
            'google_maps_embed' => Setting::get('google_maps_embed'),
            'statistik_nelayan' => Setting::get('statistik_nelayan'),
            'statistik_produksi' => Setting::get('statistik_produksi'),
            'statistik_pokdakan' => Setting::get('statistik_pokdakan'),
            'statistik_layanan' => Setting::get('statistik_layanan'),
        ];

        return view('cms.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_website' => 'required|string|max:200',
            'email' => 'required|email',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
            'favicon' => 'nullable|file|mimes:ico,png|max:10240',
        ]);

        $fields = [
            'nama_website', 'tagline', 'deskripsi', 'email', 'telepon', 'fax',
            'alamat', 'jam_operasional', 'facebook_url', 'instagram_url',
            'youtube_url', 'twitter_url', 'teks_footer', 'google_maps_embed',
            'statistik_nelayan', 'statistik_produksi', 'statistik_pokdakan', 'statistik_layanan'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $val = $request->input($field);
                if ($field === 'google_maps_embed' && !empty($val)) {
                    if (preg_match('/src=["\']([^"\']+)["\']/', $val, $matches)) {
                        $val = $matches[1];
                    }
                }
                if ($field === 'alamat' && !empty($val)) {
                    $val = trim(strip_tags($val));
                }
                Setting::set($field, $val);
            }
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $favPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $favPath);
        }

        ActivityLog::record('update_settings', 'Pengaturan Website', "Memperbarui konfigurasi global website");

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
