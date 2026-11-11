<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('seo')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    {{-- Font dimuat tanpa memblokir render; teks tampil dulu dengan font sistem lalu berganti (display=swap) --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"></noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <link rel="stylesheet" href="{{ asset('css/home-base.css') }}?v={{ filemtime(public_path('css/home-base.css')) }}">
    @stack('head')
</head>
<body>
    @php $active = trim($__env->yieldContent('active')); @endphp

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                @include('home._brand')
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ $active === 'about' ? 'active' : '' }}">Tentang</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link {{ $active === 'products' ? 'active' : '' }}">Produk</a></li>
                <li><a href="{{ route('blog.index') }}" class="nav-link {{ $active === 'blog' ? 'active' : '' }}">Blog</a></li>
                <li><a href="{{ route('home') }}#clients" class="nav-link">Klien</a></li>
                <li><a href="{{ route('home') }}#faq" class="nav-link">FAQ</a></li>
                <li><a href="{{ route('home') }}#contact" class="nav-link nav-link-btn">Kontak</a></li>
            </ul>
            <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMobileNav()">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <nav class="mobile-nav" id="mobileNav">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
        <a href="{{ route('about') }}" @class(['active' => $active === 'about'])><i class="fa-solid fa-circle-info"></i> Tentang</a>
        <a href="{{ route('products.index') }}" @class(['active' => $active === 'products'])><i class="fa-solid fa-box-open"></i> Produk</a>
        <a href="{{ route('blog.index') }}" @class(['active' => $active === 'blog'])><i class="fa-solid fa-newspaper"></i> Blog</a>
        <a href="{{ route('home') }}#clients"><i class="fa-solid fa-building-user"></i> Klien</a>
        <a href="{{ route('home') }}#faq"><i class="fa-solid fa-circle-question"></i> FAQ</a>
        <a href="{{ route('home') }}#contact"><i class="fa-solid fa-envelope"></i> Kontak</a>
    </nav>

    @yield('content')

    <footer>
        <div class="container">
            @include('home._social')
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    @if(!empty($s['wa_number']))
    <a href="https://wa.me/{{ $s['wa_number'] }}?text={{ trim($__env->yieldContent('wa-text')) ?: 'Halo%2C+saya+ingin+bertanya' }}" class="float-wa" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    @endif

    <script src="{{ asset('js/home-nav.js') }}?v={{ filemtime(public_path('js/home-nav.js')) }}"></script>
    @stack('scripts')
</body>
</html>
