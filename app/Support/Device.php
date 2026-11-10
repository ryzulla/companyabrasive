<?php

namespace App\Support;

use Illuminate\Http\Request;

class Device
{
    /**
     * Deteksi perangkat mobile via User-Agent.
     * Bisa dipaksa untuk pengujian: ?view=mobile atau ?view=desktop.
     */
    public static function isMobile(Request $request): bool
    {
        if ($request->query('view') === 'mobile') {
            return true;
        }
        if ($request->query('view') === 'desktop') {
            return false;
        }

        $ua = (string) $request->header('User-Agent');

        if ($ua === '' || preg_match('/iPad|Tablet|PlayBook|Silk|Kindle/i', $ua)) {
            return false; // tablet & UA kosong → tampilan desktop
        }

        return (bool) preg_match(
            '/Mobile|Android.+Mobile|iPhone|iPod|BlackBerry|IEMobile|Opera Mini|webOS|Windows Phone/i',
            $ua
        );
    }
}
