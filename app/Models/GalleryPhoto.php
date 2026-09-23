<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_album_id',
        'title',
        'source_type',
        'image',
        'external_url',
        'description',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    /**
     * Helper URL embed Instagram
     */
    public function getInstagramEmbedUrlAttribute(): ?string
    {
        return \App\Services\MediaSourceService::getInstagramEmbedUrl($this->external_url);
    }

    /**
     * Helper URL Foto Lokal
     */
    public function getImageUrlAttribute(): ?string
    {
        if (($this->source_type ?? 'upload') === 'upload' && !empty($this->image)) {
            if (str_starts_with($this->image, 'assets/')) {
                return asset($this->image);
            }
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
