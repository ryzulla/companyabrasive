<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public const PENDING = 'pending';
    public const APPROVED = 'approved';

    protected $fillable = ['text', 'author', 'pos', 'status'];

    /** Hanya testimoni yang sudah disetujui admin yang tampil di website. */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::APPROVED);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }
}
