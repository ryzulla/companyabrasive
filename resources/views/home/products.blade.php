@extends('home.layouts.inner')

@section('seo')
    @include('home._seo', ['page' => $seo])
@endsection

@section('active', 'products')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/home-products.css') }}?v={{ filemtime(public_path('css/home-products.css')) }}">
@endpush

@section('wa-text', 'Halo%2C+saya+ingin+bertanya+tentang+produk+Anda')

@section('content')
    {{-- PAGE HEADER --}}
    <section class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="bc-current">Katalog Produk</span>
            </div>
            <h1 id="catalogHeading">{{ $heading }}</h1>
            <p>{{ $s['products_desc'] ?? 'Temukan produk yang sesuai dengan kebutuhan industri Anda.' }}</p>
        </div>
    </section>

    {{-- SEARCH & FILTER BAR --}}
    <div class="search-filter-bar">
        <div class="container">
            {{-- Row 1: Search + Count --}}
            <div class="search-row">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input
                        type="text"
                        id="searchInput"
                        class="search-input"
                        placeholder="Cari nama produk, deskripsi..."
                        value="{{ request('q') }}"
                        autocomplete="off"
                    >
                    <button id="clearSearch" class="clear-btn" title="Hapus pencarian" style="display:{{ request('q') ? 'flex' : 'none' }}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="result-count" id="resultCount">
                    <strong>{{ $products->total() }}</strong> produk
                </div>
                <div class="view-toggle" role="group" aria-label="Tampilan produk">
                    <button type="button" class="vt-btn" data-view="grid" onclick="setProdView('grid')" aria-label="Tampilan thumbnail" title="Thumbnail"><i class="fa-solid fa-table-cells-large"></i></button>
                    <button type="button" class="vt-btn" data-view="list" onclick="setProdView('list')" aria-label="Tampilan daftar" title="Daftar"><i class="fa-solid fa-list"></i></button>
                </div>
            </div>

            {{-- Row 2: Category Pills (wraps automatically) --}}
            <div class="pills-row" id="categoryPills">
                <a href="{{ route('products.index') }}" class="category-pill {{ !request('category') ? 'active' : '' }}" data-cat="">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="category-pill {{ request('category') == $cat->id ? 'active' : '' }}" data-cat="{{ $cat->id }}">
                        {{ $cat->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PRODUCTS SECTION --}}
    <section class="products-section">
        <div class="container">

            {{-- Active Filter Info (JS-controlled) --}}
            <div class="active-filter-bar" id="activeFilterBar" style="display:{{ (request('q') || request('category')) ? 'flex' : 'none' }}">
                <span id="activeFilterText">
                    @if(request('q') || request('category'))
                        Menampilkan
                        @if(request('q')) untuk "<strong>{{ request('q') }}</strong>"@endif
                        @if(request('category'))
                            @php $activeCat = $categories->firstWhere('id', request('category')); @endphp
                            @if($activeCat) dalam <strong>{{ $activeCat->title }}</strong>@endif
                        @endif
                    @endif
                </span>
                <button class="reset-btn" onclick="resetFilter()">
                    <i class="fa-solid fa-xmark"></i> Reset
                </button>
            </div>

            {{-- Product Grid --}}
            <div class="product-grid" id="productGrid">
                @forelse($products as $p)
                    <div class="product-card" onclick="location.href='{{ route('products.show', $p, false) }}'">
                        <div class="product-img-wrapper">
                            @if($p->badge)
                                <span class="product-badge">{{ $p->badge }}</span>
                            @endif
                            @if($p->category)
                                <span class="product-category-tag">{{ $p->category->title }}</span>
                            @endif
                            <img
                                src="{{ $p->img ? Storage::url($p->img) : 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80' }}"
                                alt="{{ $p->title }}"
                                loading="lazy"
                            >
                        </div>
                        <div class="product-info">
                            <div class="product-meta">{{ $p->meta }}</div>
                            <h3 class="product-title"><a href="{{ route('products.show', $p, false) }}">{{ $p->title }}</a></h3>
                            <p class="product-desc">{{ $p->desc }}</p>
                            <div class="product-footer">
                                <div class="product-spec">
                                    {{ $p->spec_label }}:
                                    <span>{{ $p->spec_val }}</span>
                                </div>
                                <div class="card-actions">
                                    @if(!empty($s['wa_number']))
                                    <button class="btn-icon btn-icon-wa"
                                        onclick="event.stopPropagation(); window.open('https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+tertarik+dengan+{{ urlencode($p->title) }}', '_blank')"
                                        title="Tanya via WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </button>
                                    @endif
                                    <button class="btn-icon btn-icon-info" title="Detail Produk">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>Produk tidak ditemukan</h3>
                        <p>
                            @if(request('q') || request('category'))
                                Tidak ada produk yang cocok dengan filter Anda.
                                <a href="{{ route('products.index') }}">Tampilkan semua produk</a>
                            @else
                                Belum ada produk yang ditambahkan.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION (JS-rendered) --}}
            <div class="pagination-wrapper" id="paginationWrapper"></div>
            <div class="pagination-info" id="paginationInfo"></div>
        </div>
    </section>

    {{-- FOOTER --}}
@endsection

@push('scripts')
    <script>
        window.PAGE = {
            searchUrl: @json(route('products.search', [], false)),
            waNumber: @json($s['wa_number'] ?? ''),
            products: @json($productsJson),
            q: @json(request('q', '')),
            category: @json(request('category', '')),
            page: {{ (int) request('page', 1) }},
            pagination: {
                total: {{ $products->total() }},
                currentPage: {{ $products->currentPage() }},
                lastPage: {{ $products->lastPage() }},
                from: {{ $products->firstItem() ?? 0 }},
                to: {{ $products->lastItem() ?? 0 }},
            },
        };
    </script>
    <script src="{{ asset('js/home-products.js') }}?v={{ filemtime(public_path('js/home-products.js')) }}"></script>
@endpush
