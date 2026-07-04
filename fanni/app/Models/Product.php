<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'meta', 'title', 'badge',
        'img', 'desc', 'spec_label', 'spec_val',
        'rpm', 'material',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
