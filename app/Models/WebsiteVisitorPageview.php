<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteVisitorPageview extends Model
{
    protected $fillable = [
        'website_visitor_id',
        'url',
        'page_name',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(WebsiteVisitor::class, 'website_visitor_id');
    }
}
