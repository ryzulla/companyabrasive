<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'title', 'desc', 'img'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
