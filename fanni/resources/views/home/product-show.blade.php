@php $placeholder = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=800&q=80'; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $company  = $s['company_name'] ?? 'Company Profile';
        $seoDesc  = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $product->meta . '. ' . $product->desc)), 155);
        $canonical = route('products.show', $product);
        $imgAbs   = $images->map(fn($i) => str_starts_with($i, 'http') ? $i : url($i))->values();
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => $product->title,
            'description' => $seoDesc,
            'image'    => $imgAbs->all(),
            'category' => $product->category?->title,
            'material' => $product->material,
            'brand'    => ['@type' => 'Brand', 'name' => $company],
            'url'      => $canonical,
        ];
        $breadcrumb = [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Produk', 'item' => route('products.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->title, 'item' => $canonical],
            ],
        ];
    @endphp
    @include('home._seo', ['page' => [
        'title'       => $product->title . ' | ' . $company,
        'description' => $seoDesc,
        'canonical'   => $canonical,
        'image'       => $imgAbs->first(),
        'og_type'     => 'product',
        'jsonld'      => [$jsonLd, $breadcrumb],
    ]])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0f172a;
            --secondary: #475569;
            --accent: #ea580c;
            --accent-hover: #c2410c;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --transition: all 0.3s ease;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--light-bg); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        .btn-primary { background-color: var(--accent); color: #fff; padding: 12px 30px; border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); border: none; cursor: pointer; font-size: 0.95rem; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: translateY(-2px); }

        /* NAVBAR */
        header { background-color: rgba(255,255,255,0.97); position: sticky; top: 0; width: 100%; z-index: 1000; box-shadow: 0 2px 16px rgba(0,0,0,0.07); backdrop-filter: blur(12px); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .logo { font-size: 1.3rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
        .logo span { color: var(--accent); }
        .nav-menu { display: flex; gap: 22px; align-items: center; }
        .nav-link { font-weight: 500; color: var(--secondary); transition: var(--transition); font-size: 0.92rem; }
        .nav-link:hover, .nav-link.active { color: var(--accent); }
        .nav-link-btn { background: var(--accent); color: #fff !important; padding: 8px 18px; border-radius: 6px; font-size: 0.88rem; }
        .nav-link-btn:hover { background: var(--accent-hover); }
        .hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; cursor: pointer; background: none; border: none; padding: 6px; border-radius: 6px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--primary); border-radius: 2px; transition: all 0.3s ease; }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
        .mobile-nav { display: none; position: sticky; top: 72px; left: 0; right: 0; background: rgba(255,255,255,0.98); z-index: 999; box-shadow: 0 8px 20px rgba(0,0,0,0.08); padding: 8px 20px 14px; flex-direction: column; border-top: 1px solid #f1f5f9; }
        .mobile-nav.open { display: flex; }
        .mobile-nav a { padding: 12px 0; color: var(--primary); font-weight: 500; font-size: 0.93rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
        .mobile-nav a:last-child { border-bottom: none; }
        .mobile-nav a i { width: 18px; color: var(--accent); font-size: 0.82rem; }

        /* PAGE HEADER */
        .page-header { background: linear-gradient(135deg, var(--primary) 0%, #1e293b 100%); padding: 50px 0 40px; color: #fff; }
        .page-header h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 8px; }
        .page-header p { color: #94a3b8; font-size: 1rem; }
        .breadcrumb { display: flex; gap: 8px; align-items: center; font-size: 0.85rem; color: #64748b; margin-bottom: 15px; }
        .breadcrumb a { color: #94a3b8; transition: var(--transition); }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb i { font-size: 0.7rem; }

        /* PRODUCT GRID */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; margin-bottom: 50px; }

        /* PRODUCT CARD */
        .product-card { background-color: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: var(--transition); border: 1px solid #f1f5f9; cursor: pointer; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-color: #e2e8f0; }
        .product-img-wrapper { position: relative; aspect-ratio: 1 / 1; background-color: #f1f5f9; overflow: hidden; }
        .product-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .product-card:hover .product-img-wrapper img { transform: scale(1.06); }
        .product-badge { position: absolute; top: 12px; left: 12px; background-color: var(--primary); color: #fff; padding: 3px 10px; font-size: 0.72rem; font-weight: 600; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.3px; }
        .product-category-tag { position: absolute; top: 12px; right: 12px; background: rgba(234,88,12,0.9); color: #fff; padding: 3px 10px; font-size: 0.72rem; font-weight: 500; border-radius: 20px; }
        .product-info { padding: 18px 20px 16px; flex-grow: 1; display: flex; flex-direction: column; }
        .product-meta { font-size: 0.73rem; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .product-title { font-size: 1.05rem; color: var(--primary); font-weight: 600; margin-bottom: 8px; line-height: 1.4; }
        .product-desc { font-size: 0.83rem; color: var(--secondary); margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1; }
        .product-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 13px; }
        .product-spec { font-size: 0.75rem; color: var(--secondary); }
        .product-spec span { display: block; font-weight: 600; color: var(--primary); font-size: 0.82rem; }
        .card-actions { display: flex; gap: 6px; }
        .btn-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: var(--transition); font-size: 0.85rem; border: none; cursor: pointer; }
        .btn-icon-wa { background: #e8fdf0; color: #25d366; }
        .btn-icon-wa:hover { background: #25d366; color: #fff; }
        .btn-icon-info { background: #e2e8f0; color: var(--primary); }
        .btn-icon-info:hover { background: var(--accent); color: #fff; }

        /* FOOTER */
        footer { background-color: #020617; color: #64748b; padding: 28px 0; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; font-size: 0.88rem; }
        footer span { color: var(--accent); }

        /* FLOAT WA */
        .float-wa { position: fixed; width: 56px; height: 56px; bottom: 28px; right: 28px; background-color: #25d366; color: #FFF; border-radius: 50%; text-align: center; font-size: 26px; box-shadow: 0 4px 15px rgba(37,211,102,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; animation: pulse-wa 2s infinite; }
        .float-wa:hover { background-color: #128C7E; transform: scale(1.1); color: #fff; }
        @keyframes pulse-wa { 0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.7); } 70% { box-shadow: 0 0 0 15px rgba(37,211,102,0); } 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); } }


        /* PRODUCT DETAIL */
        .detail-section { padding: 36px 0 20px; }
        .detail-grid { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); gap: 40px; align-items: start; }
        .gallery-main { position: relative; background: #f1f5f9; border-radius: 14px; overflow: hidden; aspect-ratio: 1 / 1; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .gallery-main img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .gallery-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border-radius: 50%; border: none; background: rgba(255,255,255,0.92); color: var(--primary); cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.15); transition: var(--transition); }
        .gallery-nav:hover { background: var(--accent); color: #fff; }
        .gallery-prev { left: 12px; }
        .gallery-next { right: 12px; }
        .gallery-count { position: absolute; right: 12px; bottom: 12px; background: rgba(15,23,42,0.75); color: #fff; font-size: 0.75rem; padding: 3px 10px; border-radius: 20px; }
        .gallery-thumbs { display: flex; gap: 10px; margin-top: 12px; overflow-x: auto; padding-bottom: 4px; }
        .gallery-thumb { flex: 0 0 84px; height: 64px; border-radius: 8px; overflow: hidden; border: 2px solid transparent; padding: 0; background: #f1f5f9; cursor: pointer; opacity: 0.65; transition: var(--transition); }
        .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .gallery-thumb:hover { opacity: 1; }
        .gallery-thumb.active { border-color: var(--accent); opacity: 1; }

        .detail-info .badge { display: inline-block; background: var(--primary); color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; margin-bottom: 12px; }
        .detail-info .cat-tag { display: inline-block; background: rgba(234,88,12,0.1); color: var(--accent); padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; margin: 0 0 12px 6px; }
        .detail-info h1 { color: var(--primary); font-size: 1.9rem; line-height: 1.3; margin-bottom: 14px; }
        .detail-info .desc { color: var(--secondary); margin-bottom: 22px; font-size: 0.97rem; white-space: pre-line; }
        .detail-specs { background: #fff; border: 1px solid #e2e8f0; padding: 6px 18px; border-radius: 10px; margin-bottom: 26px; }
        .detail-specs div { display: flex; justify-content: space-between; gap: 16px; border-bottom: 1px solid #e2e8f0; padding: 11px 0; font-size: 0.9rem; }
        .detail-specs div:last-child { border: none; }
        .detail-specs span { color: var(--secondary); }
        .detail-specs strong { color: var(--primary); font-weight: 600; text-align: right; }
        .wa-btn { display: flex; align-items: center; justify-content: center; gap: 10px; background: #25d366; color: #fff; padding: 14px; border-radius: 8px; font-size: 0.97rem; font-weight: 600; transition: background 0.3s; }
        .wa-btn:hover { background: #1da851; }
        .quote-btn { display: flex; align-items: center; justify-content: center; gap: 10px; background: var(--accent); color: #fff; padding: 14px; border-radius: 8px; font-size: 0.97rem; font-weight: 600; transition: background 0.3s; margin-top: 10px; }
        .quote-btn:hover { background: var(--accent-hover); }
        .back-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; color: var(--secondary); font-size: 0.9rem; }
        .back-link:hover { color: var(--accent); }

        .related-wrap { padding: 30px 0 60px; }
        .related-heading { color: var(--primary); font-size: 1.4rem; font-weight: 600; margin-bottom: 20px; padding-top: 30px; border-top: 1px solid #e2e8f0; }
        .related-wrap .product-grid { grid-template-columns: repeat(4, 1fr); margin-bottom: 0; }

        .gallery-main img { cursor: zoom-in; }
        .gallery-zoom-hint { position: absolute; left: 12px; bottom: 12px; background: rgba(15,23,42,0.75); color: #fff; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; pointer-events: none; display: flex; align-items: center; gap: 6px; }

        /* LIGHTBOX + ZOOM */
        .lb { position: fixed; inset: 0; z-index: 3000; background: rgba(2,6,23,0.94); display: none; flex-direction: column; user-select: none; -webkit-user-select: none; }
        .lb.open { display: flex; }
        .lb-bar { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; color: #e2e8f0; font-size: 0.9rem; flex-shrink: 0; }
        .lb-tools { display: flex; gap: 8px; }
        .lb-btn { width: 40px; height: 40px; border-radius: 50%; border: none; background: rgba(255,255,255,0.12); color: #fff; cursor: pointer; font-size: 1rem; transition: var(--transition); display: inline-flex; align-items: center; justify-content: center; }
        .lb-btn:hover { background: var(--accent); }
        .lb-stage { flex: 1; position: relative; overflow: hidden; touch-action: none; cursor: zoom-in; display: flex; align-items: center; justify-content: center; }
        .lb-stage.zoomed { cursor: grab; }
        .lb-stage.dragging { cursor: grabbing; }
        .lb-stage img { max-width: 100%; max-height: 100%; object-fit: contain; transform-origin: center center; will-change: transform; -webkit-user-drag: none; pointer-events: none; }
        .lb-nav { position: absolute; top: 50%; transform: translateY(-50%); z-index: 2; }
        .lb-prev { left: 14px; } .lb-next { right: 14px; }
        .lb-thumbs { display: flex; gap: 8px; justify-content: center; padding: 10px 12px 14px; overflow-x: auto; flex-shrink: 0; }
        .lb-thumbs button { flex: 0 0 64px; height: 48px; padding: 0; border-radius: 6px; overflow: hidden; border: 2px solid transparent; opacity: 0.55; cursor: pointer; background: #0f172a; }
        .lb-thumbs button.active { border-color: var(--accent); opacity: 1; }
        .lb-thumbs img { width: 100%; height: 100%; object-fit: cover; display: block; }

        @media (max-width: 992px) {
            .detail-grid { grid-template-columns: 1fr; gap: 28px; }
            .related-wrap .product-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .nav-menu { display: none; }
            .page-header { padding: 36px 0 28px; }
            .detail-info h1 { font-size: 1.5rem; }
            .gallery-nav { width: 34px; height: 34px; }
        }
        @media (max-width: 480px) {
            .container { padding: 0 14px; }
            .product-info { padding: 12px 14px; }
            .product-title { font-size: 0.9rem; }
            .related-wrap .product-grid { gap: 12px; }
            .float-wa { width: 50px; height: 50px; bottom: 18px; right: 18px; font-size: 22px; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                @include('home._brand')
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link">Tentang</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link active">Produk</a></li>
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
        <a href="{{ route('about') }}"><i class="fa-solid fa-circle-info"></i> Tentang</a>
        <a href="{{ route('products.index') }}" style="color:var(--accent)"><i class="fa-solid fa-box-open"></i> Produk</a>
        <a href="{{ route('home') }}#clients"><i class="fa-solid fa-building-user"></i> Klien</a>
        <a href="{{ route('home') }}#faq"><i class="fa-solid fa-circle-question"></i> FAQ</a>
        <a href="{{ route('home') }}#contact"><i class="fa-solid fa-envelope"></i> Kontak</a>
    </nav>

    <section class="page-header" style="padding: 28px 0 24px;">
        <div class="container">
            <div class="breadcrumb" style="margin-bottom:0; flex-wrap:wrap;">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <a href="{{ route('products.index') }}">Produk</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span style="color: #fff;">{{ $product->title }}</span>
            </div>
        </div>
    </section>

    {{-- DETAIL PRODUK --}}
    <section class="detail-section">
        <div class="container">
            <div class="detail-grid">
                <div class="gallery">
                    <div class="gallery-main">
                        <img id="galleryMain" src="{{ $images[0] ?? $placeholder }}" alt="{{ $product->title }}" onclick="openLightbox()">
                        <span class="gallery-zoom-hint"><i class="fa-solid fa-magnifying-glass-plus"></i> Klik untuk zoom</span>
                        @if($images->count() > 1)
                            <button type="button" class="gallery-nav gallery-prev" onclick="galleryStep(-1)" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
                            <button type="button" class="gallery-nav gallery-next" onclick="galleryStep(1)" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
                            <span class="gallery-count" id="galleryCount">1 / {{ $images->count() }}</span>
                        @endif
                    </div>
                    @if($images->count() > 1)
                        <div class="gallery-thumbs">
                            @foreach($images as $i => $url)
                                <button type="button" class="gallery-thumb {{ $i === 0 ? 'active' : '' }}" onclick="galleryGo({{ $i }})" aria-label="Foto {{ $i + 1 }}">
                                    <img src="{{ $url }}" alt="" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="detail-info">
                    <div>
                        <span class="badge">{{ $product->meta }}</span>
                        @if($product->category)<span class="cat-tag">{{ $product->category->title }}</span>@endif
                    </div>
                    <h1>{{ $product->title }}</h1>
                    <p class="desc">{{ $product->desc }}</p>
                    <div class="detail-specs">
                        <div><span>{{ $product->spec_label }}</span> <strong>{{ $product->spec_val }}</strong></div>
                        @if(filled($product->rpm))<div><span>Kecepatan Maks (RPM)</span> <strong>{{ $product->rpm }}</strong></div>@endif
                        <div><span>Material Aplikasi</span> <strong>{{ $product->material }}</strong></div>
                    </div>
                    @if(!empty($s['wa_number']))
                    <a class="wa-btn" target="_blank" rel="noopener"
                       href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya ingin memesan '.$product->title) }}">
                        <i class="fa-brands fa-whatsapp"></i> Pesan via WhatsApp
                    </a>
                    @endif
                    <a class="quote-btn" href="{{ route('home', ['produk' => $product->slug]) }}#contact">
                        <i class="fa-solid fa-file-invoice"></i> Minta Penawaran
                    </a>
                    <a href="{{ route('products.index') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke katalog</a>
                </div>
            </div>
        </div>
    </section>

    @if($related->isNotEmpty())
    <section class="related-wrap">
        <div class="container">
            <h2 class="related-heading">Produk Serupa</h2>
            <div class="product-grid">
                @foreach($related as $r)
                    <a href="{{ route('products.show', $r) }}" class="product-card">
                        <div class="product-img-wrapper">
                            @if($r->badge)<span class="product-badge">{{ $r->badge }}</span>@endif
                            <img src="{{ $r->img ? Storage::url($r->img) : $placeholder }}" alt="{{ $r->title }}" loading="lazy">
                        </div>
                        <div class="product-info">
                            <div class="product-meta">{{ $r->meta }}</div>
                            <h3 class="product-title">{{ $r->title }}</h3>
                            <div class="product-footer">
                                <div class="product-spec">{{ $r->spec_label }}:<span>{{ $r->spec_val }}</span></div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- LIGHTBOX / ZOOM --}}
    <div class="lb" id="lightbox" aria-hidden="true">
        <div class="lb-bar">
            <span id="lbCount"></span>
            <div class="lb-tools">
                <button type="button" class="lb-btn" onclick="lbZoomBy(-0.5)" aria-label="Perkecil"><i class="fa-solid fa-minus"></i></button>
                <button type="button" class="lb-btn" onclick="lbZoomBy(0.5)" aria-label="Perbesar"><i class="fa-solid fa-plus"></i></button>
                <button type="button" class="lb-btn" onclick="lbReset()" aria-label="Reset zoom"><i class="fa-solid fa-expand"></i></button>
                <button type="button" class="lb-btn" onclick="closeLightbox()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <div class="lb-stage" id="lbStage">
            <img id="lbImg" src="" alt="{{ $product->title }}" draggable="false">
            @if($images->count() > 1)
                <button type="button" class="lb-btn lb-nav lb-prev" onclick="galleryStep(-1)" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
                <button type="button" class="lb-btn lb-nav lb-next" onclick="galleryStep(1)" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
            @endif
        </div>
        @if($images->count() > 1)
            <div class="lb-thumbs" id="lbThumbs">
                @foreach($images as $i => $url)
                    <button type="button" onclick="galleryGo({{ $i }})" aria-label="Foto {{ $i + 1 }}"><img src="{{ $url }}" alt=""></button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            @include('home._social')
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    @if(!empty($s['wa_number']))
    <a href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya tertarik dengan '.$product->title) }}" class="float-wa" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    @endif

    <script>
        const GALLERY = @json($images);
        let galleryIdx = 0;

        function galleryGo(i) {
            if (!GALLERY.length) return;
            galleryIdx = (i + GALLERY.length) % GALLERY.length;
            document.getElementById('galleryMain').src = GALLERY[galleryIdx];
            document.querySelectorAll('.gallery-thumb').forEach((t, n) => t.classList.toggle('active', n === galleryIdx));
            const c = document.getElementById('galleryCount');
            if (c) c.textContent = (galleryIdx + 1) + ' / ' + GALLERY.length;
            if (lb.classList.contains('open')) lbSync();
        }
        function galleryStep(d) { galleryGo(galleryIdx + d); }

        // ── LIGHTBOX + ZOOM
        const lb = document.getElementById('lightbox');
        const lbStage = document.getElementById('lbStage');
        const lbImg = document.getElementById('lbImg');
        const LB_MIN = 1, LB_MAX = 5;
        let z = { scale: 1, x: 0, y: 0 };
        const pointers = new Map();
        let dragStart = null, pinchStart = null, moved = false;

        function lbApply() {
            lbImg.style.transform = `translate(${z.x}px, ${z.y}px) scale(${z.scale})`;
            lbStage.classList.toggle('zoomed', z.scale > 1);
        }
        function lbClamp() {
            // batasi geser agar gambar tidak keluar jauh dari layar
            const mx = Math.max(0, (lbImg.clientWidth * z.scale - lbStage.clientWidth) / 2);
            const my = Math.max(0, (lbImg.clientHeight * z.scale - lbStage.clientHeight) / 2);
            z.x = Math.min(mx, Math.max(-mx, z.x));
            z.y = Math.min(my, Math.max(-my, z.y));
        }
        function lbReset() { z = { scale: 1, x: 0, y: 0 }; lbApply(); }
        function lbSetScale(next, cx, cy) {
            next = Math.min(LB_MAX, Math.max(LB_MIN, next));
            if (cx !== undefined) {
                // zoom mengarah ke titik kursor/jari
                const r = lbStage.getBoundingClientRect();
                const px = cx - (r.left + r.width / 2), py = cy - (r.top + r.height / 2);
                const k = next / z.scale;
                z.x = px - (px - z.x) * k;
                z.y = py - (py - z.y) * k;
            }
            z.scale = next;
            if (next === 1) { z.x = 0; z.y = 0; }
            lbClamp(); lbApply();
        }
        function lbZoomBy(d) { lbSetScale(z.scale + d); }
        function lbSync() {
            lbImg.src = GALLERY[galleryIdx];
            document.getElementById('lbCount').textContent = GALLERY.length > 1 ? (galleryIdx + 1) + ' / ' + GALLERY.length : '';
            document.querySelectorAll('#lbThumbs button').forEach((b, n) => b.classList.toggle('active', n === galleryIdx));
            lbReset();
        }
        function openLightbox() {
            if (!GALLERY.length) return;
            lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            lbSync();
        }
        function closeLightbox() {
            lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        lbStage.addEventListener('wheel', e => {
            e.preventDefault();
            lbSetScale(z.scale * (e.deltaY < 0 ? 1.2 : 1 / 1.2), e.clientX, e.clientY);
        }, { passive: false });

        lbStage.addEventListener('pointerdown', e => {
            if (e.target.closest('.lb-btn')) return;
            lbStage.setPointerCapture(e.pointerId);
            pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
            moved = false;
            if (pointers.size === 1) dragStart = { x: e.clientX, y: e.clientY, ox: z.x, oy: z.y };
            if (pointers.size === 2) {
                const [a, b] = [...pointers.values()];
                pinchStart = { d: Math.hypot(a.x - b.x, a.y - b.y), scale: z.scale };
            }
        });
        lbStage.addEventListener('pointermove', e => {
            if (!pointers.has(e.pointerId)) return;
            pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
            if (pointers.size === 2 && pinchStart) {
                const [a, b] = [...pointers.values()];
                lbSetScale(pinchStart.scale * Math.hypot(a.x - b.x, a.y - b.y) / pinchStart.d, (a.x + b.x) / 2, (a.y + b.y) / 2);
                moved = true;
            } else if (pointers.size === 1 && dragStart) {
                const dx = e.clientX - dragStart.x, dy = e.clientY - dragStart.y;
                if (Math.abs(dx) + Math.abs(dy) > 4) moved = true;
                if (z.scale > 1) {
                    z.x = dragStart.ox + dx; z.y = dragStart.oy + dy;
                    lbClamp(); lbApply();
                    lbStage.classList.add('dragging');
                }
            }
        });
        function lbPointerEnd(e) {
            const wasSingle = pointers.size === 1;
            const start = dragStart;
            pointers.delete(e.pointerId);
            lbStage.classList.remove('dragging');
            if (pointers.size < 2) pinchStart = null;
            if (pointers.size === 0) {
                // geser horizontal cepat saat belum di-zoom = pindah foto
                if (wasSingle && start && z.scale === 1 && e.type === 'pointerup') {
                    const dx = e.clientX - start.x;
                    if (Math.abs(dx) > 60 && Math.abs(e.clientY - start.y) < 60) { galleryStep(dx < 0 ? 1 : -1); dragStart = null; return; }
                }
                // ketuk/klik tanpa geser = toggle zoom
                if (!moved && wasSingle && e.type === 'pointerup') lbSetScale(z.scale > 1 ? 1 : 2.5, e.clientX, e.clientY);
                dragStart = null;
            }
        }
        lbStage.addEventListener('pointerup', lbPointerEnd);
        lbStage.addEventListener('pointercancel', lbPointerEnd);

        document.addEventListener('keydown', e => {
            const open = lb.classList.contains('open');
            if (e.key === 'Escape' && open) closeLightbox();
            if (e.key === 'ArrowLeft') galleryStep(-1);
            if (e.key === 'ArrowRight') galleryStep(1);
            if (open && (e.key === '+' || e.key === '=')) lbZoomBy(0.5);
            if (open && e.key === '-') lbZoomBy(-0.5);
        });
        window.addEventListener('resize', () => { if (lb.classList.contains('open')) { lbClamp(); lbApply(); } });

        // swipe di foto utama (layar sentuh); ketukan biasa membuka zoom lewat onclick
        (() => {
            const el = document.querySelector('.gallery-main');
            let x0 = null, swiped = false;
            el.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; swiped = false; }, { passive: true });
            el.addEventListener('touchend', e => {
                if (x0 === null) return;
                const dx = e.changedTouches[0].clientX - x0;
                if (Math.abs(dx) > 40) { swiped = true; galleryStep(dx < 0 ? 1 : -1); }
                x0 = null;
            });
            el.addEventListener('click', e => { if (swiped) { e.stopImmediatePropagation(); e.preventDefault(); swiped = false; } }, true);
        })();

        function toggleMobileNav() {
            document.getElementById('mobileNav').classList.toggle('open');
            document.getElementById('hamburger').classList.toggle('open');
        }
    </script>
</body>
</html>
