<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;

class CmsRoleController extends Controller
{
    public function index()
    {
        $matrix = [
            ['feature' => 'Lihat Halaman Publik', 'super_admin' => true, 'admin' => true, 'public' => true],
            ['feature' => 'Dashboard CMS', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Berita & Kategori', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Layanan & Bidang', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Galeri Foto & Video', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Dokumen & Download', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Banner / Slider', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen Profil Dinas & Organisasi', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Pesan Masuk (Inbox Kontak)', 'super_admin' => true, 'admin' => true, 'public' => false],
            ['feature' => 'Manajemen User Pengelola', 'super_admin' => true, 'admin' => false, 'public' => false],
            ['feature' => 'Manajemen Role & Hak Akses', 'super_admin' => true, 'admin' => false, 'public' => false],
            ['feature' => 'Pengaturan Website Global', 'super_admin' => true, 'admin' => false, 'public' => false],
            ['feature' => 'Log Aktivitas Sistem', 'super_admin' => true, 'admin' => false, 'public' => false],
        ];

        return view('cms.role.index', compact('matrix'));
    }
}
