<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const SUBJECTS = [
        'Permintaan Penawaran',
        'Tanya Produk',
        'Kerja Sama / Distributor',
        'Lainnya',
    ];

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];
}
