@extends('home.layouts.mobile-page')

@php
    $q = request('q');
    $cat = request('category', 'all');
    $catTitle = $cat !== 'all' ? optional($categories->firstWhere('id', $cat))->title : null;
    $placeholder = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';
@endphp

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => ($catTitle ? $catTitle . ' | ' : '') . 'Katalog Produk | ' . ($s['company_name'] ?? 'Company Profile'),
        'description' => $s['products_desc'] ?? 'Katalog amplas, batu gerinda, roda poles, dan perlengkapan industri.',
        'canonical'   => route('products.index', array_filter(['category' => $cat !== 'all' ? $cat : null])),
        'robots'      => ($q || $products->currentPage() > 1) ? 'noindex, follow' : null,
    ]])
@endsection

@section('tab-products', 'active')

@section('content')
    <div class="search-wrap">
        <form method="GET" action="{{ route('products.index') }}">
            @if($cat !== 'all')<input type="hidden" name="category" value="{{ $cat }}">@endif
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari produk..." autocomplete="off" enterkeyhint="search" @if(request()->boolean('cari')) autofocus @endif>
                @if($q)<a href="{{ route('products.index', array_filter(['category' => $cat !== 'all' ? $cat : null])) }}" aria-label="Hapus pencarian"><i class="fa-solid fa-xmark"></i></a>@endif
            </div>
        </form>
    </div>

    <div class="chips">
        <a class="chip {{ $cat === 'all' ? 'active' : '' }}" href="{{ route('products.index', array_filter(['q' => $q])) }}">Semua</a>
        @foreach($categories as $c)
            <a class="chip {{ $cat === $c->id ? 'active' : '' }}" href="{{ route('products.index', array_filter(['category' => $c->id, 'q' => $q])) }}">{{ $c->title }}</a>
        @endforeach
    </div>

    <div class="pl-count">
        @if($products->total())
            Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk
        @endif
    </div>

    <div class="prod-grid">
        @forelse($products as $p)
            <a href="{{ route('products.show', $p) }}" class="prod-card">
                <div class="prod-img">
                    @if($p->badge)<span class="prod-badge">{{ $p->badge }}</span>@endif
                    <img src="{{ $p->img ? Storage::url($p->img) : $placeholder }}" alt="{{ $p->title }}" loading="lazy">
                </div>
                <div class="prod-body">
                    <span class="prod-meta">{{ $p->meta }}</span>
                    <h4 class="prod-title">{{ $p->title }}</h4>
                    <div class="prod-foot">
                        <div class="prod-spec">{{ $p->spec_label }}<b>{{ $p->spec_val ?: '-' }}</b></div>
                    </div>
                </div>
            </a>
        @empty
            <div class="pl-empty">Produk tidak ditemukan.</div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="pad pad-t6">
            <div class="pg-row">
                @if($products->onFirstPage())<span class="pg-btn disabled">‹ Sebelumnya</span>@else<a class="pg-btn" href="{{ $products->previousPageUrl() }}" rel="prev">‹ Sebelumnya</a>@endif
                <span class="pg-info">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                @if($products->hasMorePages())<a class="pg-btn" href="{{ $products->nextPageUrl() }}" rel="next">Berikutnya ›</a>@else<span class="pg-btn disabled">Berikutnya ›</span>@endif
            </div>
        </div>
    @endif
@endsection
