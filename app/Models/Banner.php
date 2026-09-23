<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link_type',     // 'none', 'berita', 'pelayanan', 'external'
        'news_id',
        'service_id',
        'external_url',
        'image_source',   // 'content', 'custom'
        'link',           // Dipertahankan untuk backward compatibility
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'news_id' => 'integer',
        'service_id' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Relasi ke model News (Berita)
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * Relasi ke model Service (Pelayanan)
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Scope untuk banner aktif yang diurutkan
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }

    /**
     * URL Tujuan Dinamis (Real-time slug resolution)
     */
    public function getTargetUrlAttribute(): ?string
    {
        if ($this->link_type === 'berita') {
            $news = $this->relationLoaded('news') ? $this->news : $this->news;
            return ($news && $news->slug) ? route('news.show', $news->slug) : null;
        }

        if ($this->link_type === 'pelayanan') {
            $service = $this->relationLoaded('service') ? $this->service : $this->service;
            return ($service && $service->slug) ? route('service.show', $service->slug) : null;
        }

        if ($this->link_type === 'external') {
            return $this->external_url ?: $this->link;
        }

        if ($this->link_type === 'none') {
            return null;
        }

        // Backward compatibility khusus banner lama tanpa link_type terdefinisi
        return $this->link;
    }

    /**
     * Safe SVG Data URI placeholder for banners (zero network request, zero 404 risk)
     */
    public const DEFAULT_PLACEHOLDER = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 400' width='100%25' height='100%25'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23003F88;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23001D4A;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='1200' height='400' fill='url(%23grad)'/%3E%3Ccircle cx='1100' cy='80' r='180' fill='%230077B6' opacity='0.25'/%3E%3Ccircle cx='100' cy='320' r='140' fill='%2300B4D8' opacity='0.15'/%3E%3Cpath d='M0 340 Q 300 280 600 340 T 1200 340 L 1200 400 L 0 400 Z' fill='%23001838' opacity='0.6'/%3E%3Cg transform='translate(600, 175)' text-anchor='middle'%3E%3Crect x='-50' y='-70' width='100' height='60' rx='10' fill='%23F4A100' opacity='0.2'/%3E%3Cpath d='M-20 -35 L -8 -50 L 8 -30 L 20 -45 L 35 -20 L -35 -20 Z' fill='%23F4A100'/%3E%3Ctext y='25' font-family='system-ui, -apple-system, sans-serif' font-size='28' font-weight='bold' fill='%23FFFFFF'%3EBANNER HERO SLIDER%3C/text%3E%3Ctext y='55' font-family='system-ui, -apple-system, sans-serif' font-size='16' fill='%2390E0EF'%3EDinas Perikanan Kabupaten Banyumas%3C/text%3E%3C/g%3E%3C/svg%3E";

    /**
     * Sumber Gambar Dinamis (Real-time content image resolution)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_source === 'content') {
            if ($this->link_type === 'berita') {
                $news = $this->relationLoaded('news') ? $this->news : $this->news;
                if ($news && !empty($news->thumbnail)) {
                    return $this->formatPathToUrl($news->thumbnail);
                }
            } elseif ($this->link_type === 'pelayanan') {
                $service = $this->relationLoaded('service') ? $this->service : $this->service;
                if ($service && !empty($service->icon)) {
                    return $this->formatPathToUrl($service->icon);
                }
            }
        }

        if (!empty($this->image)) {
            return $this->formatPathToUrl($this->image);
        }

        return self::DEFAULT_PLACEHOLDER;
    }

    /**
     * Helper formatting path storage/asset ke URL publik yang aman
     */
    protected function formatPathToUrl(?string $path): string
    {
        if (empty($path)) {
            return self::DEFAULT_PLACEHOLDER;
        }

        // URL absolut eksternal atau data URI
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:image/')) {
            return $path;
        }

        // Tangani path legacy yang tidak tersedia di filesystem
        if ($path === 'assets/images/placeholder-banner.jpg') {
            return self::DEFAULT_PLACEHOLDER;
        }

        if (str_starts_with($path, 'assets/')) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            return self::DEFAULT_PLACEHOLDER;
        }

        return asset('storage/' . $path);
    }
}
