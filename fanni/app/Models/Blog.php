<?php

namespace App\Models;

use App\Support\ArticleHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['img', 'meta', 'title', 'desc'];

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
