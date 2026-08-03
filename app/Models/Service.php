<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'penanggung_jawab_id',
        'title',
        'slug',
        'description',
        'requirements',
        'procedure',
        'duration',
        'cost',
        'product',
        'icon',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Relasi tunggal (backward compatibility)
     */
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id');
    }

    /**
     * Relasi banyak penanggung jawab (Many-to-Many dengan Pivot keterangan)
     */
    public function penanggungJawabList(): BelongsToMany
    {
        return $this->belongsToMany(PenanggungJawab::class, 'penanggung_jawab_service', 'service_id', 'penanggung_jawab_id')
                    ->withPivot('keterangan')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
