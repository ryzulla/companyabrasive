@extends('home.layouts.inner')

@php $placeholder = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=800&q=80'; @endphp

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

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => $product->title . ' | ' . $company,
        'description' => $seoDesc,
        'canonical'   => $canonical,
        'image'       => $imgAbs->first(),
        'og_type'     => 'product',
        'jsonld'      => [$jsonLd, $breadcrumb],
    ]])
@endsection

@section('active', 'products')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/home-product-show.css') }}?v={{ filemtime(public_path('css/home-product-show.css')) }}">
@endpush

@section('wa-text', urlencode('Halo, saya tertarik dengan '.$product->title))

@section('content')
    <section class="page-header page-header--compact">
        <div class="container">
            <div class="breadcrumb breadcrumb--tight">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <a href="{{ route('products.index') }}">Produk</a>
                <i class="fa-solid fa-chevron-right"></i>
                @if($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->title }}</a>
                <i class="fa-solid fa-chevron-right"></i>
                @endif
                <span class="bc-current">{{ $product->title }}</span>
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
                        @if($product->category)<a class="cat-tag" href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->title }}</a>@endif
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
@endsection

@push('scripts')
    <script>
        window.PAGE = { images: @json($images) };
    </script>
    <script src="{{ asset('js/home-product-show.js') }}?v={{ filemtime(public_path('js/home-product-show.js')) }}"></script>
@endpush
