<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'session_id',
        'browser',
        'device',
        'operating_system',
        'url',
        'page_name',
        'referer',
        'visited_at',
        'last_activity',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'last_activity' => 'datetime',
    ];

    public function pageviews()
    {
        return $this->hasMany(WebsiteVisitorPageview::class, 'website_visitor_id');
    }
}
