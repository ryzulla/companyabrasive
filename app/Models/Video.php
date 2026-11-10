<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['id', 'title', 'desc'];
    protected $keyType = 'string';
    public $incrementing = false;
}
