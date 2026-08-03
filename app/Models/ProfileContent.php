<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileContent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'updated_at',
    ];

    public static function getContent(string $key, string $default = ''): string
    {
        $content = self::where('key', $key)->first();
        return $content ? $content->value ?? '' : $default;
    }
}
