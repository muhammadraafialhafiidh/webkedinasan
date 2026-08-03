<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenanggungJawab extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab';

    protected $fillable = [
        'nama',
        'nomor_hp',
    ];

    /**
     * Relasi HasMany (legacy/single)
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'penanggung_jawab_id');
    }

    /**
     * Relasi Banyak Layanan (Many-to-Many dengan Pivot keterangan)
     */
    public function servicesList(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'penanggung_jawab_service', 'penanggung_jawab_id', 'service_id')
                    ->withPivot('keterangan')
                    ->withTimestamps();
    }

    /**
     * Accessor untuk mendapatkan nomor HP yang sudah dibersihkan (Format 628xxx)
     */
    public function getFormattedWaNumberAttribute(): ?string
    {
        if (empty($this->nomor_hp)) {
            return null;
        }

        // Hapus karakter +, -, spasi, dan non-angka
        $clean = preg_replace('/[^0-9]/', '', $this->nomor_hp);

        // Jika diawali dengan '0', ubah menjadi '62'
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }

        return $clean;
    }

    /**
     * Accessor untuk Tautan Direct WhatsApp
     */
    public function getWaLinkAttribute(): ?string
    {
        $formatted = $this->formatted_wa_number;

        if (empty($formatted)) {
            return null;
        }

        return 'https://wa.me/' . $formatted;
    }
}
