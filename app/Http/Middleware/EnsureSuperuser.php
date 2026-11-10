<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Membatasi halaman master data (pengaturan, pengguna, kategori, klien) hanya untuk superuser. */
class EnsureSuperuser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isSuperuser()) {
            if ($request->expectsJson()) {
                abort(403, 'Hanya superuser yang dapat mengakses fitur ini.');
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut. Fitur ini hanya untuk Superuser.');
        }

        return $next($request);
    }
}
