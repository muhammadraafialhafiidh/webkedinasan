<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class, 'gallery_album_id');
    }
}
