<?php

use App\Http\Controllers\Cms\ActivityLogController;
use App\Http\Controllers\Cms\AuthController;
use App\Http\Controllers\Cms\CmsActivityLogController;
use App\Http\Controllers\Cms\CmsAlbumController;
use App\Http\Controllers\Cms\CmsBannerController;
use App\Http\Controllers\Cms\CmsContactController;
use App\Http\Controllers\Cms\CmsDocumentController;
use App\Http\Controllers\Cms\CmsNewsCategoryController;
use App\Http\Controllers\Cms\CmsNewsController;
use App\Http\Controllers\Cms\CmsOrganizationController;
use App\Http\Controllers\Cms\CmsPenanggungJawabController;
use App\Http\Controllers\Cms\CmsPhotoController;
use App\Http\Controllers\Cms\CmsProfileController;
use App\Http\Controllers\Cms\CmsRoleController;
use App\Http\Controllers\Cms\CmsServiceCategoryController;
use App\Http\Controllers\Cms\CmsServiceController;
use App\Http\Controllers\Cms\CmsSettingController;
use App\Http\Controllers\Cms\CmsUserController;
use App\Http\Controllers\Cms\CmsVideoController;
use App\Http\Controllers\Cms\CmsVisitorStatisticController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\ForgotPasswordController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DocumentController;
use App\Http\Controllers\Public\GalleryPhotoController;
use App\Http\Controllers\Public\GalleryVideoController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil
Route::get('/profil', [ProfileController::class, 'index'])->name('profile');

// Berita
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// Layanan
Route::get('/layanan', [ServiceController::class, 'index'])->name('service.index');
Route::get('/layanan/kategori/{slug}', [ServiceController::class, 'byCategory'])->name('service.byCategory');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('service.show');

// Galeri
Route::get('/galeri/foto', [GalleryPhotoController::class, 'index'])->name('gallery.photo');
Route::get('/galeri/foto/{album}', [GalleryPhotoController::class, 'show'])->name('gallery.photo.show');
Route::get('/galeri/video', [GalleryVideoController::class, 'index'])->name('gallery.video');

// Dokumen & Download
Route::get('/dokumen', [DocumentController::class, 'index'])->name('document.index');

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| CMS Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cms')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('cms.login');
    Route::post('/login', [AuthController::class, 'login'])->name('cms.login.post');
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showForgotPassword'])->name('cms.lupa-password');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLink'])->name('cms.lupa-password.post');
    Route::get('/reset-password-expired', [ForgotPasswordController::class, 'showExpiredLink'])->name('cms.reset-password.expired');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPassword'])->name('cms.reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('cms.reset-password.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('cms.logout');
});

/*
|--------------------------------------------------------------------------
| CMS Back-Office Routes (Admin & Super Admin)
|--------------------------------------------------------------------------
*/

Route::prefix('cms')->middleware(['cms.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('cms.dashboard');

    // Statistik Website
    Route::get('/statistik', [CmsVisitorStatisticController::class, 'index'])->name('cms.statistik.index');
    Route::get('/statistik/live', [CmsVisitorStatisticController::class, 'live'])->name('cms.statistik.live');

    // Berita
    Route::get('/berita', [CmsNewsController::class, 'index'])->name('cms.berita.index');
    Route::get('/berita/tambah', [CmsNewsController::class, 'create'])->name('cms.berita.create');
    Route::post('/berita', [CmsNewsController::class, 'store'])->name('cms.berita.store');
    Route::get('/berita/{id}/edit', [CmsNewsController::class, 'edit'])->name('cms.berita.edit');
    Route::put('/berita/{id}', [CmsNewsController::class, 'update'])->name('cms.berita.update');
    Route::delete('/berita/{id}', [CmsNewsController::class, 'destroy'])->name('cms.berita.destroy');
    Route::put('/berita/{id}/toggle', [CmsNewsController::class, 'toggleStatus'])->name('cms.berita.toggle');

    // Kategori Berita
    Route::get('/kategori-berita', [CmsNewsCategoryController::class, 'index'])->name('cms.kategori-berita.index');
    Route::post('/kategori-berita', [CmsNewsCategoryController::class, 'store'])->name('cms.kategori-berita.store');
    Route::put('/kategori-berita/{id}', [CmsNewsCategoryController::class, 'update'])->name('cms.kategori-berita.update');
    Route::delete('/kategori-berita/{id}', [CmsNewsCategoryController::class, 'destroy'])->name('cms.kategori-berita.destroy');

    // Kategori Layanan
    Route::get('/kategori-layanan', [CmsServiceCategoryController::class, 'index'])->name('cms.kategori-layanan.index');
    Route::post('/kategori-layanan', [CmsServiceCategoryController::class, 'store'])->name('cms.kategori-layanan.store');
    Route::put('/kategori-layanan/{id}', [CmsServiceCategoryController::class, 'update'])->name('cms.kategori-layanan.update');
    Route::delete('/kategori-layanan/{id}', [CmsServiceCategoryController::class, 'destroy'])->name('cms.kategori-layanan.destroy');

    // Penanggung Jawab Layanan
    Route::get('/penanggung-jawab', [CmsPenanggungJawabController::class, 'index'])->name('cms.penanggung-jawab.index');
    Route::post('/penanggung-jawab', [CmsPenanggungJawabController::class, 'store'])->name('cms.penanggung-jawab.store');
    Route::put('/penanggung-jawab/{id}', [CmsPenanggungJawabController::class, 'update'])->name('cms.penanggung-jawab.update');
    Route::delete('/penanggung-jawab/{id}', [CmsPenanggungJawabController::class, 'destroy'])->name('cms.penanggung-jawab.destroy');

    // Layanan
    Route::get('/layanan', [CmsServiceController::class, 'index'])->name('cms.layanan.index');
    Route::get('/layanan/tambah', [CmsServiceController::class, 'create'])->name('cms.layanan.create');
    Route::post('/layanan', [CmsServiceController::class, 'store'])->name('cms.layanan.store');
    Route::get('/layanan/{id}/edit', [CmsServiceController::class, 'edit'])->name('cms.layanan.edit');
    Route::put('/layanan/{id}', [CmsServiceController::class, 'update'])->name('cms.layanan.update');
    Route::delete('/layanan/{id}', [CmsServiceController::class, 'destroy'])->name('cms.layanan.destroy');
    Route::put('/layanan/{id}/toggle', [CmsServiceController::class, 'toggleActive'])->name('cms.layanan.toggle');

    // Galeri Foto & Album
    Route::get('/galeri/album', [CmsAlbumController::class, 'index'])->name('cms.galeri.album.index');
    Route::post('/galeri/album', [CmsAlbumController::class, 'store'])->name('cms.galeri.album.store');
    Route::put('/galeri/album/{id}', [CmsAlbumController::class, 'update'])->name('cms.galeri.album.update');
    Route::delete('/galeri/album/{id}', [CmsAlbumController::class, 'destroy'])->name('cms.galeri.album.destroy');
    Route::get('/galeri/foto/{albumId}', [CmsPhotoController::class, 'showAlbumPhotos'])->name('cms.galeri.foto.index');
    Route::post('/galeri/foto/{albumId}', [CmsPhotoController::class, 'store'])->name('cms.galeri.foto.store');
    Route::delete('/galeri/foto/{id}', [CmsPhotoController::class, 'destroy'])->name('cms.galeri.foto.destroy');

    // Galeri Video
    Route::get('/galeri/video', [CmsVideoController::class, 'index'])->name('cms.galeri.video.index');
    Route::post('/galeri/video', [CmsVideoController::class, 'store'])->name('cms.galeri.video.store');
    Route::put('/galeri/video/{id}', [CmsVideoController::class, 'update'])->name('cms.galeri.video.update');
    Route::delete('/galeri/video/{id}', [CmsVideoController::class, 'destroy'])->name('cms.galeri.video.destroy');

    // Dokumen
    Route::get('/dokumen', [CmsDocumentController::class, 'index'])->name('cms.dokumen.index');
    Route::post('/dokumen', [CmsDocumentController::class, 'store'])->name('cms.dokumen.store');
    Route::put('/dokumen/{id}', [CmsDocumentController::class, 'update'])->name('cms.dokumen.update');
    Route::delete('/dokumen/{id}', [CmsDocumentController::class, 'destroy'])->name('cms.dokumen.destroy');

    // Banner / Slider
    Route::get('/banner', [CmsBannerController::class, 'index'])->name('cms.banner.index');
    Route::post('/banner', [CmsBannerController::class, 'store'])->name('cms.banner.store');
    Route::put('/banner/{id}', [CmsBannerController::class, 'update'])->name('cms.banner.update');
    Route::delete('/banner/{id}', [CmsBannerController::class, 'destroy'])->name('cms.banner.destroy');
    Route::put('/banner/{id}/toggle', [CmsBannerController::class, 'toggleActive'])->name('cms.banner.toggle');

    // Profil Dinas (Sejarah, Visi, Misi, Tupoksi)
    Route::get('/profil', [CmsProfileController::class, 'index'])->name('cms.profil.index');
    Route::put('/profil', [CmsProfileController::class, 'update'])->name('cms.profil.update');

    // Struktur Organisasi
    Route::get('/organisasi', [CmsOrganizationController::class, 'index'])->name('cms.organisasi.index');
    Route::post('/organisasi', [CmsOrganizationController::class, 'store'])->name('cms.organisasi.store');
    Route::put('/organisasi/{id}', [CmsOrganizationController::class, 'update'])->name('cms.organisasi.update');
    Route::delete('/organisasi/{id}', [CmsOrganizationController::class, 'destroy'])->name('cms.organisasi.destroy');

    // Alias untuk profil.organisasi (compatibility)
    Route::post('/profil/organisasi', [CmsOrganizationController::class, 'store'])->name('cms.profil.organisasi.store');
    Route::put('/profil/organisasi/{id}', [CmsOrganizationController::class, 'update'])->name('cms.profil.organisasi.update');
    Route::delete('/profil/organisasi/{id}', [CmsOrganizationController::class, 'destroy'])->name('cms.profil.organisasi.destroy');

    // Pesan Masuk (Kontak)
    Route::get('/pesan', [CmsContactController::class, 'index'])->name('cms.pesan.index');
    Route::get('/pesan/{id}', [CmsContactController::class, 'show'])->name('cms.pesan.show');
    Route::post('/pesan/{id}/reply', [CmsContactController::class, 'reply'])->name('cms.pesan.reply');
    Route::put('/pesan/{id}/read', [CmsContactController::class, 'markRead'])->name('cms.pesan.read');
    Route::delete('/pesan/{id}', [CmsContactController::class, 'destroy'])->name('cms.pesan.destroy');
});

/*
|--------------------------------------------------------------------------
| Super Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cms')->middleware(['cms.auth', 'role:super_admin'])->group(function () {
    // Manajemen User Admin
    Route::get('/user', [CmsUserController::class, 'index'])->name('cms.user.index');
    Route::get('/user/tambah', [CmsUserController::class, 'create'])->name('cms.user.create');
    Route::post('/user', [CmsUserController::class, 'store'])->name('cms.user.store');
    Route::get('/user/{id}/edit', [CmsUserController::class, 'edit'])->name('cms.user.edit');
    Route::put('/user/{id}', [CmsUserController::class, 'update'])->name('cms.user.update');
    Route::delete('/user/{id}', [CmsUserController::class, 'destroy'])->name('cms.user.destroy');
    Route::put('/user/{id}/reset-password', [CmsUserController::class, 'resetPassword'])->name('cms.user.reset-password');

    // Matrix Hak Akses / Role Management
    Route::get('/role', [CmsRoleController::class, 'index'])->name('cms.role.index');

    // Global Settings Website
    Route::get('/pengaturan', [CmsSettingController::class, 'index'])->name('cms.pengaturan.index');
    Route::put('/pengaturan', [CmsSettingController::class, 'update'])->name('cms.pengaturan.update');

    // Activity Audit Logs
    Route::get('/log-aktivitas', [CmsActivityLogController::class, 'index'])->name('cms.log-aktivitas.index');
    Route::get('/log-aktivitas/export', [CmsActivityLogController::class, 'export'])->name('cms.log-aktivitas.export');
});
