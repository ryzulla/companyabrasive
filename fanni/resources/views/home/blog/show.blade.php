@extends('home.layouts.inner')

@php
    $company  = $s['company_name'] ?? 'Company Profile';
    $cover    = $blog->img ? Storage::url($blog->img) : null;
    $url      = route('blog.show', $blog);
    $jsonLd   = [
        '@context' => 'https://schema.org', '@type' => 'Article',
        'headline' => $blog->title,
        'description' => $blog->excerpt(155),
        'image' => $cover ? [url($cover)] : [],
        'datePublished' => $blog->created_at?->toAtomString(),
        'dateModified' => $blog->updated_at?->toAtomString(),
        'mainEntityOfPage' => $url,
        'author' => ['@type' => 'Organization', 'name' => $company],
        'publisher' => ['@type' => 'Organization', 'name' => $company],
    ];
@endphp

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => $blog->title . ' | ' . $company,
        'description' => $blog->excerpt(155),
        'canonical'   => $url,
        'image'       => $cover ? url($cover) : null,
        'og_type'     => 'article',
        'jsonld'      => [$jsonLd],
    ]])
@endsection

@section('nav-blog', 'active')

@section('content')
    <section class="page-header" style="padding: 36px 0 30px;">
        <div class="container">
            <div class="breadcrumb" style="flex-wrap: wrap;">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <a href="{{ route('blog.index') }}">Blog</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span style="color: #fff;">{{ \Illuminate\Support\Str::limit($blog->title, 60) }}</span>
            </div>
            <h1 style="max-width: 820px;">{{ $blog->title }}</h1>
            <p>{{ $blog->meta }} · {{ $blog->created_at?->translatedFormat('d F Y') }}</p>
        </div>
    </section>

    <article class="container" style="padding-top: 44px; padding-bottom: 60px;">
        <div class="article-wrap">
            @if($cover)<img class="article-cover" src="{{ $cover }}" alt="{{ $blog->title }}">@endif
            <div class="rich-content">{!! $blog->body_html !!}</div>

            <div class="article-share">
                <span>Bagikan:</span>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . $url) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <button type="button" aria-label="Salin link" onclick="navigator.clipboard.writeText('{{ $url }}').then(() => { this.innerHTML = '<i class=&quot;fa-solid fa-check&quot;></i>'; })"><i class="fa-regular fa-copy"></i></button>
            </div>
        </div>
    </article>

    @if($related->isNotEmpty())
    <section class="container related-articles">
        <h2>Artikel Lainnya</h2>
        <div class="blog-grid">
            @foreach($related as $r)
                <a href="{{ route('blog.show', $r) }}" class="blog-card">
                    <div class="blog-img" style="background-image: url('{{ $r->img ? Storage::url($r->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80' }}')"></div>
                    <div class="blog-content">
                        <div class="blog-meta">{{ $r->meta }}</div>
                        <h3>{{ $r->title }}</h3>
                        <p>{{ $r->excerpt(100) }}</p>
                        <span class="read-more">Baca Artikel <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif
@endsection
