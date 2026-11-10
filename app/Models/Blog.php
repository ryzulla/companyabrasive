<?php

namespace App\Models;

use App\Support\ArticleHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['img', 'meta', 'title', 'slug', 'desc'];

    protected static function booted(): void
    {
        // Slug dibuat sekali saat artikel dibuat, supaya URL tidak berubah walau judul diedit.
        static::creating(function (Blog $blog) {
            if (blank($blog->slug)) {
                $blog->slug = static::uniqueSlug($blog->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'artikel';
        $slug = $base;
        $i = 2;
        while (ctype_digit($slug) || static::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . $i++;
        }

        return $slug;
    }

    /** Isi artikel yang sudah dibersihkan dan siap ditampilkan di website. */
    protected function bodyHtml(): Attribute
    {
        return Attribute::get(fn () => ArticleHtml::render($this->desc));
    }

    /** Cuplikan teks polos untuk kartu artikel. */
    public function excerpt(int $limit = 100): string
    {
        return ArticleHtml::excerpt($this->desc, $limit);
    }
}
