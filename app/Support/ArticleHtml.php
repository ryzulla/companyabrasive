<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Menyiapkan isi artikel (HTML dari CKEditor) untuk ditampilkan di website:
 * - teks polos lama dikonversi ke paragraf,
 * - hanya tag/atribut aman yang dipertahankan,
 * - <oembed> (video) diubah menjadi iframe YouTube / Vimeo yang responsif.
 */
class ArticleHtml
{
    private const ALLOWED = [
        'p' => [], 'br' => [], 'hr' => [], 'h2' => [], 'h3' => [], 'h4' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [],
        'a' => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'srcset', 'sizes'],
        'figure' => ['class'], 'figcaption' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
        'div' => ['class'],
    ];

    /** Elemen yang dibuang beserta isinya (bukan sekadar dibuka bungkusnya). */
    private const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'svg', 'math', 'noscript'];

    private const CLASSES = ['image', 'image-style-side', 'image-style-align-left', 'image-style-align-right', 'image-style-align-center', 'image_resized', 'media', 'table', 'video-embed'];

    public static function render(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        // Teks polos lama (tanpa tag) → paragraf
        if ($html === strip_tags($html)) {
            return '<p>' . nl2br(e($html)) . '</p>';
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $doc->getElementById('__root');
        if (! $root) {
            return '<p>' . e(strip_tags($html)) . '</p>';
        }

        $trusted = new \SplObjectStorage();
        self::convertEmbeds($doc, $root, $trusted);
        self::clean($root, $trusted);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return $out;
    }

    public static function excerpt(?string $html, int $limit = 100): string
    {
        $html = preg_replace('~<(script|style)\b.*?</\1>~is', ' ', (string) $html);
        $html = str_replace('<', ' <', $html); // pisahkan teks antar-blok agar kata tidak menempel
        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return \Illuminate\Support\Str::limit($text, $limit);
    }

    /** <oembed url="..."> → <div class="video-embed"><iframe …></div> bila penyedianya dikenal. */
    private static function convertEmbeds(DOMDocument $doc, DOMElement $root, \SplObjectStorage $trusted): void
    {
        foreach (iterator_to_array($root->getElementsByTagName('oembed')) as $oembed) {
            $src = self::embedUrl((string) $oembed->getAttribute('url'));
            $figure = $oembed->parentNode;

            if ($src) {
                $wrap = $doc->createElement('div');
                $wrap->setAttribute('class', 'video-embed');
                $frame = $doc->createElement('iframe');
                $frame->setAttribute('src', $src);
                $frame->setAttribute('loading', 'lazy');
                $frame->setAttribute('allow', 'accelerometer; encrypted-media; gyroscope; picture-in-picture; fullscreen');
                $frame->setAttribute('allowfullscreen', '');
                $frame->setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
                $frame->setAttribute('title', 'Video');
                $wrap->appendChild($frame);
                $trusted->attach($wrap);
                $trusted->attach($frame);
                $replace = ($figure instanceof DOMElement && $figure->tagName === 'figure') ? $figure : $oembed;
                $replace->parentNode->replaceChild($wrap, $replace);
            } else {
                // penyedia tidak dikenal → tampilkan sebagai tautan biasa
                $url = (string) $oembed->getAttribute('url');
                $p = $doc->createElement('p');
                if (self::safeUrl($url)) {
                    $a = $doc->createElement('a', e($url));
                    $a->setAttribute('href', $url);
                    $p->appendChild($a);
                }
                $replace = ($figure instanceof DOMElement && $figure->tagName === 'figure') ? $figure : $oembed;
                $replace->parentNode->replaceChild($p, $replace);
            }
        }
    }

    public static function embedUrl(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{6,20})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d{5,12})~i', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }

    private static function clean(DOMNode $node, \SplObjectStorage $trusted): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (! $child instanceof DOMElement) {
                if ($child->nodeType === XML_COMMENT_NODE) {
                    $node->removeChild($child);
                }
                continue;
            }
            if ($trusted->contains($child)) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);
                continue;
            }

            self::clean($child, $trusted);

            if (! array_key_exists($tag, self::ALLOWED)) {
                // tag tak dikenal: buang pembungkusnya, pertahankan isinya
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::cleanAttributes($child, $tag);
        }
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        $allowed = self::ALLOWED[$tag];

        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if (! in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->name);
                continue;
            }
            if (in_array($name, ['href', 'src'], true) && ! self::safeUrl($attr->value, $name === 'src')) {
                $el->removeAttribute($attr->name);
            }
        }

        if ($el->hasAttribute('class')) {
            $keep = array_intersect(preg_split('/\s+/', $el->getAttribute('class')), self::CLASSES);
            $keep ? $el->setAttribute('class', implode(' ', $keep)) : $el->removeAttribute('class');
        }

        if ($tag === 'a' && $el->hasAttribute('href')) {
            $el->setAttribute('rel', 'noopener noreferrer');
            if (preg_match('~^https?://~i', $el->getAttribute('href'))) {
                $el->setAttribute('target', '_blank');
            } else {
                $el->removeAttribute('target');
            }
        }

        if ($tag === 'img') {
            $el->setAttribute('loading', 'lazy');
            if (! $el->hasAttribute('src')) {
                $el->parentNode?->removeChild($el);
            }
        }
    }

    private static function safeUrl(string $url, bool $image = false): bool
    {
        $url = trim($url);
        if ($url === '') {
            return false;
        }
        if (preg_match('~^(https?:)?//~i', $url) || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }
        if (! $image && preg_match('~^(mailto|tel):~i', $url)) {
            return true;
        }

        // URL relatif tanpa skema (mis. "storage/x.jpg"); tolak skema lain seperti javascript: / data:
        return ! preg_match('~^[a-z][a-z0-9+.-]*:~i', $url);
    }
}
