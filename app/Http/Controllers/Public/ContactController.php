<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $rawMap = Setting::get('google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.3805690737654!2d109.23927857379906!3d-7.423066973115903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e8eb3deee57%3A0xd4af385dd314aa20!2sDinas%20Perikanan%20dan%20Peternakan%20Kabupaten%20Banyumas!5e0!3m2!1sid!2sid!4v1784863442328!5m2!1sid!2sid');
        
        $mapUrl = $rawMap;
        if (preg_match('/src=["\']([^"\']+)["\']/', $rawMap, $matches)) {
            $mapUrl = $matches[1];
        }

        $info = [
            'alamat' => trim(strip_tags(Setting::get('alamat', 'Jl. Merdeka No. 1, Purwokerto, Jawa Tengah 53111'))),
            'telepon' => trim(strip_tags(Setting::get('telepon', '(0281) 123456'))),
            'email' => trim(strip_tags(Setting::get('email', 'info@perikanan.go.id'))),
            'jam' => trim(strip_tags(Setting::get('jam_operasional', 'Senin–Jumat: 08.00–16.00 WIB'))),
            'map' => $mapUrl,
        ];

        $breadcrumbs = [
            ['label' => 'Kontak Kami', 'url' => null],
        ];

        return view('public.contact.index', compact('info', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan Anda berhasil terkirim. Terima kasih telah menghubungi Dinas Perikanan.');
    }
}
