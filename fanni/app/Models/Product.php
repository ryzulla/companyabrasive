<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'meta', 'title', 'slug', 'badge',
        'img', 'desc', 'spec_label', 'spec_val',
        'rpm', 'material',
    ];

    protected static function booted(): void
    {
        // Slug dibuat sekali saat produk dibuat, supaya URL tidak berubah walau judul diedit.
        static::creating(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = static::uniqueSlug($product->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'produk';
        $slug = $base;
        $i = 2;
        while (ctype_digit($slug) || static::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . $i++;
        }

        return $slug;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort')->orderBy('id');
    }

    /** Samakan kolom `img` (cover di kartu produk) dengan foto pertama. */
    public function syncCover(): void
    {
        $this->update(['img' => $this->images()->value('path') ?? '']);
    }
}
