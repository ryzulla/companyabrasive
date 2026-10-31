<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialInvite extends Model
{
    protected $fillable = ['token', 'label', 'expires_at', 'used_at', 'testimonial_id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /** Huruf & angka tanpa 0/O/1/I/L agar mudah dibaca dan diketik. */
    private const ALPHABET = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
    private const TOKEN_LENGTH = 10;

    public static function generate(string $label, ?int $days): self
    {
        return static::create([
            'token' => static::newToken(),
            'label' => $label,
            'expires_at' => $days ? now()->addDays($days) : null,
        ]);
    }

    private static function newToken(): string
    {
        do {
            $token = '';
            for ($i = 0; $i < self::TOKEN_LENGTH; $i++) {
                $token .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
            }
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function url(): string
    {
        return route('testimonial.form', $this->token);
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isUsable(): bool
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }

    /** aktif | terpakai | kedaluwarsa */
    public function statusLabel(): string
    {
        return $this->isUsed() ? 'Terpakai' : ($this->isExpired() ? 'Kedaluwarsa' : 'Aktif');
    }
}
