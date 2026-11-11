@php $placeholder = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=800&q=80'; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
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
            ],
        ];
        if ($product->category) {
            $breadcrumb['itemListElement'][] = ['@type' => 'ListItem', 'position' => 3, 'name' => $product->category->title, 'item' => route('products.index', ['category' => $product->category->id])];
        }
        $breadcrumb['itemListElement'][] = ['@type' => 'ListItem', 'position' => count($breadcrumb['itemListElement']) + 1, 'name' => $product->title, 'item' => $canonical];
    @endphp
    @include('home._seo', ['page' => [
        'title'       => $product->title . ' | ' . $company,
        'description' => $seoDesc,
        'canonical'   => $canonical,
        'image'       => $imgAbs->first(),
        'og_type'     => 'product',
        'jsonld'      => [$jsonLd, $breadcrumb],
    ]])

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

    {{-- ══════════ HEADER ══════════ --}}
    <header class="app-header detail">
        <a class="back-btn" href="{{ route('products.index') }}" onclick="if (history.length > 1 && document.referrer.indexOf(location.host) !== -1) { history.back(); return false; }" aria-label="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
        <a class="app-logo" href="{{ route('home') }}">
            @include('home._brand')
        </a>
        <div class="header-actions">
            <a class="icon-btn" href="{{ route('products.index', ['cari' => 1]) }}" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></a>
            @if($hasWa)
            <a class="icon-btn wa" href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya tertarik dengan '.$product->title) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            @endif
        </div>
    </header>

    <main class="app-main has-actionbar">

        {{-- GALERI --}}
        <div class="pd-gallery">
            <div class="pd-track" id="pdTrack">
                @forelse($images as $i => $url)
                    <div class="pd-slide" onclick="openLightbox({{ $i }})">
                        <img src="{{ $url }}" alt="{{ $product->title }}" @if($i > 0) loading="lazy" @endif>
                    </div>
                @empty
                    <div class="pd-slide"><img src="{{ $placeholder }}" alt="{{ $product->title }}"></div>
                @endforelse
            </div>
            @if($images->isNotEmpty())
                <span class="pd-zoom"><i class="fa-solid fa-magnifying-glass-plus"></i> Ketuk untuk zoom</span>
            @endif
            @if($images->count() > 1)
                <span class="pd-count" id="pdCount">1 / {{ $images->count() }}</span>
            @endif
        </div>
        @if($images->count() > 1)
            <div class="pd-dots" id="pdDots">
                @foreach($images as $i => $url)<span class="{{ $i === 0 ? 'active' : '' }}"></span>@endforeach
            </div>
        @endif

        {{-- INFO --}}
        <div class="pd-body">
            <div class="pd-tags">
                <span class="pd-tag">{{ $product->meta }}</span>
                @if($product->category)<a class="pd-tag cat" href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->title }}</a>@endif
            </div>
            <h1 class="pd-title">{{ $product->title }}</h1>
            <p class="pd-desc">{{ $product->desc }}</p>

            <div class="pd-specs">
                <div><span>{{ $product->spec_label }}</span><strong>{{ $product->spec_val }}</strong></div>
                @if(filled($product->rpm))<div><span>Kecepatan Maks (RPM)</span><strong>{{ $product->rpm }}</strong></div>@endif
                <div><span>Material Aplikasi</span><strong>{{ $product->material }}</strong></div>
            </div>
        </div>

        {{-- PRODUK SERUPA --}}
        @if($related->isNotEmpty())
        <section class="pd-related">
            <div class="sec-title">Produk Serupa</div>
            <div class="rail">
                @foreach($related as $r)
                    <a href="{{ route('products.show', $r) }}" class="prod-card">
                        <div class="prod-img">
                            @if($r->badge)<span class="prod-badge">{{ $r->badge }}</span>@endif
                            <img src="{{ $r->img ? Storage::url($r->img) : $placeholder }}" alt="{{ $r->title }}" loading="lazy">
                        </div>
                        <div class="prod-body">
                            <span class="prod-meta">{{ $r->meta }}</span>
                            <h4 class="prod-title">{{ $r->title }}</h4>
                            <div class="prod-foot">
                                <div class="prod-spec">{{ $r->spec_label }}<b>{{ $r->spec_val ?: '-' }}</b></div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <div class="footer-note footer-note--page">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
    </main>

    {{-- BAR AKSI --}}
    <div class="pd-bar">
        <a class="btn btn-primary" href="{{ route('home', ['produk' => $product->slug]) }}#contact"><i class="fa-solid fa-file-invoice"></i> Minta Penawaran</a>
        @if($hasWa)
        <a class="btn btn-wa-icon" href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya ingin memesan '.$product->title) }}" target="_blank" rel="noopener" aria-label="Pesan via WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        @endif
    </div>

    {{-- TAB BAR (sama dengan beranda mobile) --}}
    <nav class="tabbar">
        <a class="tab" href="{{ route('home') }}"><i class="fa-solid fa-house"></i>Beranda</a>
        <a class="tab active" href="{{ route('home') }}#products"><i class="fa-solid fa-box-open"></i>Produk</a>
        <a class="tab" href="{{ route('home') }}#about"><i class="fa-solid fa-circle-info"></i>Tentang</a>
        <a class="tab" href="{{ route('home') }}#contact"><i class="fa-solid fa-headset"></i>Kontak</a>
    </nav>

    {{-- LIGHTBOX / ZOOM --}}
    <div class="lb" id="lightbox" aria-hidden="true">
        <div class="lb-bar">
            <span id="lbCount"></span>
            <div class="lb-tools">
                <button type="button" class="lb-btn" onclick="lbZoomBy(-0.5)" aria-label="Perkecil"><i class="fa-solid fa-minus"></i></button>
                <button type="button" class="lb-btn" onclick="lbZoomBy(0.5)" aria-label="Perbesar"><i class="fa-solid fa-plus"></i></button>
                <button type="button" class="lb-btn" onclick="closeLightbox()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <div class="lb-stage" id="lbStage">
            <img id="lbImg" src="" alt="{{ $product->title }}" draggable="false">
        </div>
        @if($images->count() > 1)
            <div class="lb-thumbs" id="lbThumbs">
                @foreach($images as $i => $url)
                    <button type="button" onclick="lbGo({{ $i }})" aria-label="Foto {{ $i + 1 }}"><img src="{{ $url }}" alt=""></button>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        window.PAGE = { images: @json($images) };
    </script>
    <script src="{{ asset('js/home-product-show-mobile.js') }}?v={{ filemtime(public_path('js/home-product-show-mobile.js')) }}"></script>
</body>
</html>
