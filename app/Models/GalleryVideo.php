<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'source_type',
        'url',
        'video_file',
        'thumbnail',
        'description',
    ];

    /**
     * Helper URL embed YouTube
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->url ?? '', $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return $this->url;
    }

    /**
     * Helper URL embed Instagram
     */
    public function getInstagramEmbedUrlAttribute(): ?string
    {
        return \App\Services\MediaSourceService::getInstagramEmbedUrl($this->url);
    }

    /**
     * Helper URL embed Google Drive
     */
    public function getGoogleDriveEmbedUrlAttribute(): ?string
    {
        return \App\Services\MediaSourceService::getGoogleDriveEmbedUrl($this->url);
    }

    /**
     * Helper URL File Video Lokal
     */
    public function getVideoFileUrlAttribute(): ?string
    {
        if ($this->source_type === 'file' && !empty($this->video_file)) {
            return asset('storage/' . $this->video_file);
        }
        return null;
    }

    /**
     * Helper Thumbnail
     */
    public function getYoutubeThumbnailAttribute(): ?string
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }
        if ($this->source_type === 'youtube' && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->url ?? '', $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
        }
        return asset('assets/images/video-placeholder.jpg');
    }
}
