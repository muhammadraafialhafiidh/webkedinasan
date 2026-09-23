<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Whitelist tag HTML yang aman untuk konten CKEditor.
     */
    protected static array $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del',
        'sub', 'sup', 'span', 'small',
        'ul', 'ol', 'li',
        'blockquote', 'q',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'a', 'hr',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
    ];

    /**
     * Protokol URL yang diizinkan untuk hyperlink.
     */
    protected static array $allowedProtocols = [
        'http://',
        'https://',
        'mailto:',
        'tel:',
    ];

    /**
     * Sanitasi konten HTML dari CKEditor agar aman dirender ke publik.
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $trimmed = trim($html);

        // Jika konten tidak mengandung tag HTML apa pun (contoh: data lama dari database),
        // bungkus dalam tag <p> dengan nl2br agar format paragraf tetap rapi.
        if (!preg_match('/<[a-z][\s\S]*>/i', $trimmed)) {
            return '<p>' . nl2br(e($trimmed)) . '</p>';
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);

        // Konversi string UTF-8 ke entitas numerik agar karakter multibyte tidak rusak pada DOMDocument
        $encoded = mb_encode_numericentity($trimmed, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8');
        $wrapped = '<div>' . $encoded . '</div>';

        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->getElementsByTagName('div')->item(0);
        if (!$root) {
            return '';
        }

        self::sanitizeNode($root, $dom);

        // Ambil inner HTML dari div pembungkus
        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        $cleanedResult = trim($result);

        // Jika hasil sanitasi tidak memiliki elemen blok, bungkus dalam <p>
        if ($cleanedResult !== '' && !preg_match('/<\s*(p|div|ul|ol|h[1-6]|blockquote|table)\b/i', $cleanedResult)) {
            $cleanedResult = '<p>' . $cleanedResult . '</p>';
        }

        return $cleanedResult;
    }

    /**
     * Rekursif membersihkan node HTML berdasarkan whitelist dan aturan keamanan.
     */
    protected static function sanitizeNode(\DOMNode $node, \DOMDocument $dom): void
    {
        // Salin child nodes ke array karena daftar node dapat termutasi selama iterasi
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->nodeName);

                // Hapus total tag berisiko tinggi beserta seluruh konten di dalamnya
                if (in_array($tagName, ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'meta', 'link'], true)) {
                    $node->removeChild($child);
                    continue;
                }

                // Jika tag tidak terdaftar dalam whitelist, unwrap tag tersebut (pertahankan teks dan children-nya)
                if (!in_array($tagName, self::$allowedTags, true)) {
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                // Bersihkan atribut
                $attributesToRemove = [];
                foreach ($child->attributes as $attr) {
                    $attrName = strtolower($attr->nodeName);

                    // Blokir seluruh inline event handlers (onclick, onmouseover, onerror, dll.)
                    if (str_starts_with($attrName, 'on')) {
                        $attributesToRemove[] = $attrName;
                        continue;
                    }

                    if ($tagName === 'a') {
                        if ($attrName === 'href') {
                            $href = trim($attr->nodeValue);
                            if (!self::isSafeUrl($href)) {
                                $attributesToRemove[] = 'href';
                            }
                        } elseif (!in_array($attrName, ['href', 'target', 'rel', 'title', 'class', 'style'], true)) {
                            $attributesToRemove[] = $attrName;
                        }
                    } elseif ($attrName === 'style') {
                        // Tolak expression atau javascript pada inline style
                        $styleValue = $attr->nodeValue;
                        if (preg_match('/(expression|javascript|behavior|vbscript)/i', $styleValue)) {
                            $attributesToRemove[] = 'style';
                        }
                    } elseif (!in_array($attrName, ['class', 'style', 'title', 'align', 'colspan', 'rowspan'], true)) {
                        $attributesToRemove[] = $attrName;
                    }
                }

                foreach ($attributesToRemove as $attrName) {
                    $child->removeAttribute($attrName);
                }

                // Penanganan khusus tag <a>
                if ($tagName === 'a') {
                    // Jika href kosong atau dihapus karena protokol berbahaya
                    if (!$child->hasAttribute('href') || trim($child->getAttribute('href')) === '') {
                        // Unwrap tag <a> menjadi teks biasa agar tidak dapat diklik atau membahayakan
                        while ($child->firstChild) {
                            $node->insertBefore($child->firstChild, $child);
                        }
                        $node->removeChild($child);
                        continue;
                    }

                    $href = trim($child->getAttribute('href'));
                    $target = strtolower(trim($child->getAttribute('target')));

                    // Jika target="_blank" atau link eksternal (http/https), set target="_blank" dan rel="noopener noreferrer"
                    if ($target === '_blank' || stripos($href, 'http://') === 0 || stripos($href, 'https://') === 0) {
                        $child->setAttribute('target', '_blank');
                        $child->setAttribute('rel', 'noopener noreferrer');
                    }
                }

                // Rekursi untuk child node
                self::sanitizeNode($child, $dom);
            } elseif ($child->nodeType === XML_COMMENT_NODE) {
                // Hapus komentar HTML
                $node->removeChild($child);
            }
        }
    }

    /**
     * Memeriksa apakah suatu URL aman untuk digunakan pada atribut href.
     */
    protected static function isSafeUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        // Cek apakah ada skema berbahaya seperti javascript:, data:, vbscript:, file:
        // Cek juga kemungkinan whitespace / control character tersembunyi
        $normalizedUrl = preg_replace('/[\x00-\x1F\x7F\s]+/', '', $url);
        if (preg_match('/^(javascript|data|vbscript|file):/i', $normalizedUrl)) {
            return false;
        }

        // Izinkan protokol yang terdaftar dalam whitelist
        foreach (self::$allowedProtocols as $protocol) {
            if (stripos($url, $protocol) === 0) {
                return true;
            }
        }

        // Izinkan path relatif atau anchor link pada domain yang sama
        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        return false;
    }
}
