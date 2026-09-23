<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'reply',
        'replied_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan balasan bersih tanpa raw HTML tag namun tetap berparagraf.
     */
    public function getCleanReplyAttribute(): string
    {
        return self::htmlToPlainText($this->reply ?? '');
    }

    /**
     * Konversi HTML dari Rich Text Editor menjadi teks bersih yang terformat rapi.
     * Mempertahankan line breaks dan pemisah paragraf tanpa menampilkan raw HTML tags.
     */
    public static function htmlToPlainText(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // 1. Ubah tag block-level dan line break menjadi newline
        $text = preg_replace('/<\/(p|div|h[1-6]|li|tr|blockquote)>/i', "\n\n", $html);
        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\/td>/i', " ", $text);

        // 2. Hapus seluruh sisa tag HTML
        $text = strip_tags($text);

        // 3. Decode HTML entities (&nbsp;, &amp;, &quot;, dll)
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 4. Ubah non-breaking space dan zero-width space menjadi spasi biasa
        $text = preg_replace('/[\x{00a0}\x{200b}\x{feff}]/u', ' ', $text);

        // 5. Normalisasi spasi dan baris kosong berlebih
        $text = preg_replace("/[ \t]+/u", " ", $text);
        $text = preg_replace("/(\r\n|\n|\r)/", "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        // 6. Trim whitespace termasuk unicode space
        return trim(preg_replace('/^\s+|\s+$/u', '', $text));
    }
}
