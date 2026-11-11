@extends('home.layouts.mobile-page')

@php
    $company = $s['company_name'] ?? 'Company Profile';
    $cover   = $blog->img ? Storage::url($blog->img) : null;
    $url     = route('blog.show', $blog);
    $logo = !empty($s['company_logo']) ? url(Storage::url($s['company_logo'])) : null;
    $publisher = array_filter([
        '@type' => 'Organization', '@id' => url('/') . '#organization', 'name' => $company, 'url' => url('/'),
        'logo' => $logo ? ['@type' => 'ImageObject', 'url' => $logo] : null,
    ]);
    $breadcrumb = [
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $blog->title, 'item' => $url],
        ],
    ];
    $jsonLd  = [
        '@context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $blog->title, 'description' => $blog->excerpt(155),
        'image' => $cover ? [url($cover)] : [],
        'datePublished' => $blog->created_at?->toAtomString(),
        'dateModified' => $blog->updated_at?->toAtomString(),
        'mainEntityOfPage' => $url,
        'inLanguage' => 'id-ID',
        'author' => $publisher,
        'publisher' => $publisher,
    ];
@endphp

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => $blog->title . ' | ' . $company,
        'description' => $blog->excerpt(155),
        'canonical'   => $url,
        'image'       => $cover ? url($cover) : null,
        'og_type'     => 'article',
        'jsonld'      => [$jsonLd, $breadcrumb],
    ]])
@endsection

@section('back', route('blog.index'))

@section('content')
    @if($cover)<img class="art-cover" src="{{ $cover }}" alt="{{ $blog->title }}">@endif
    <div class="art-body">
        <span class="pd-tag">{{ $blog->meta }}</span>
        <h1 class="pd-title mt-10">{{ $blog->title }}</h1>
        <div class="art-date">{{ $blog->created_at?->translatedFormat('d F Y') }}</div>
        <div class="rich-content">{!! $blog->body_html !!}</div>

        <div class="art-share">
            <span>Bagikan</span>
            <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . $url) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
    </div>

    @if($related->isNotEmpty())
    <div class="pad pad-t22">
        <div class="sec-title">Artikel Lainnya</div>
        @foreach($related as $r)
            <a href="{{ route('blog.show', $r) }}" class="blog-card blog-card--stack">
                <div class="blog-thumb" style="background-image: url('{{ $r->img ? Storage::url($r->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=300&q=80' }}');"></div>
                <div class="blog-info">
                    <span class="bm">{{ $r->meta }}</span>
                    <h4>{{ $r->title }}</h4>
                    <p>{{ $r->excerpt(80) }}</p>
                </div>
            </a>
        @endforeach
    </div>
    @endif
@endsection
