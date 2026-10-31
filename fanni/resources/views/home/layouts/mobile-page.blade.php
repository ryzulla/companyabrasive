<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
    @yield('seo')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <link rel="stylesheet" href="{{ asset('css/home-mobile.css') }}?v={{ filemtime(public_path('css/home-mobile.css')) }}">
</head>
<body>
    @php $hasWa = !empty($s['wa_number']); @endphp

    <header class="app-header detail">
        <a class="back-btn" href="@yield('back', route('home'))" onclick="if (history.length > 1 && document.referrer.indexOf(location.host) !== -1) { history.back(); return false; }" aria-label="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
        <a class="app-logo" href="{{ route('home') }}">
            @include('home._brand')
        </a>
        <div class="header-actions">
            @if(!empty($s['phone']))
            <a class="icon-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $s['phone']) }}" aria-label="Telepon"><i class="fa-solid fa-phone"></i></a>
            @endif
            @if($hasWa)
            <a class="icon-btn wa" href="https://wa.me/{{ $s['wa_number'] }}?text=Halo%2C+saya+ingin+bertanya" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            @endif
        </div>
    </header>

    <main class="app-main @yield('main-class')">
        @yield('content')
        <div class="footer-note footer-note--page">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
    </main>

    @yield('after-main')

    <nav class="tabbar">
        <a class="tab" href="{{ route('home') }}"><i class="fa-solid fa-house"></i>Beranda</a>
        <a class="tab @yield('tab-products')" href="{{ route('products.index') }}"><i class="fa-solid fa-box-open"></i>Produk</a>
        <a class="tab @yield('tab-about')" href="{{ route('about') }}"><i class="fa-solid fa-circle-info"></i>Tentang</a>
        <a class="tab" href="{{ route('home') }}#contact"><i class="fa-solid fa-headset"></i>Kontak</a>
    </nav>
    @stack('scripts')
</body>
</html>
