@php
    // Halaman error harus tetap tampil walau database bermasalah
    try { $s = \App\Models\Setting::allKeyed(); } catch (\Throwable $e) { $s = []; }
    $company = $s['company_name'] ?? config('app.name', 'Website');
    $wa = !empty($s['wa_number']) ? 'https://wa.me/' . $s['wa_number'] . '?text=' . urlencode('Halo, saya ingin bertanya') : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') · @yield('title') | {{ $company }}</title>
    @if(!empty($s['favicon']))<link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::url($s['favicon']) }}">@endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --secondary: #475569; --accent: #ea580c; --accent-hover: #c2410c; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', system-ui, sans-serif; }
        body { min-height: 100vh; min-height: 100svh; background: #f8fafc; color: var(--primary); display: flex; flex-direction: column; }
        .bar { padding: 18px 20px; background: #fff; border-bottom: 1px solid #eef2f7; font-weight: 700; }
        .bar a { color: var(--primary); text-decoration: none; }
        .bar span { color: var(--accent); }
        main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; text-align: center; }
        .box { max-width: 460px; }
        .code { font-size: clamp(4.5rem, 20vw, 7.5rem); font-weight: 700; line-height: 1; color: var(--accent); letter-spacing: -0.03em; }
        h1 { font-size: 1.5rem; margin: 14px 0 10px; }
        p { color: var(--secondary); font-size: 0.95rem; line-height: 1.7; }
        .actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 28px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-outline { border: 2px solid var(--accent); color: var(--accent); }
        .btn-outline:hover { background: var(--accent); color: #fff; }
        footer { padding: 18px; text-align: center; font-size: 0.78rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="bar"><a href="{{ url('/') }}">{{ $company }}</a></div>
    <main>
        <div class="box">
            <div class="code">@yield('code')</div>
            <h1>@yield('title')</h1>
            <p>@yield('message')</p>
            <div class="actions">
                <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
                <a href="{{ url('/products') }}" class="btn btn-outline">Lihat Produk</a>
                @if($wa)<a href="{{ $wa }}" class="btn btn-outline" target="_blank" rel="noopener">Hubungi Kami</a>@endif
            </div>
        </div>
    </main>
    <footer>{{ $s['footer_text'] ?? '© ' . date('Y') . ' ' . $company }}</footer>
</body>
</html>
