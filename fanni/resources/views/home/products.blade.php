<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk | {{ $s['company_name'] ?? 'Company Profile' }}</title>

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

        /* SEARCH & FILTER */
        .search-filter-bar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 14px 0 12px; position: sticky; top: 72px; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
        /* Row 1: search + count */
        .search-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
        .search-wrap { position: relative; flex: 1; display: flex; align-items: center; }
        .search-icon { position: absolute; left: 13px; color: var(--secondary); font-size: 0.85rem; pointer-events: none; z-index: 1; }
        .search-input { width: 100%; padding: 10px 38px 10px 36px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; font-family: inherit; color: var(--text-main); outline: none; transition: var(--transition); background: #f8fafc; }
        .search-input:focus { border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(234,88,12,0.08); }
        .clear-btn { position: absolute; right: 10px; background: #e2e8f0; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.7rem; color: var(--secondary); transition: var(--transition); }
        .clear-btn:hover { background: var(--accent); color: #fff; }
        .result-count { font-size: 0.84rem; color: var(--secondary); white-space: nowrap; flex-shrink: 0; }
        .result-count strong { color: var(--primary); }
        /* Row 2: category pills — always wrap */
        .pills-row { display: flex; flex-wrap: wrap; gap: 7px; align-items: center; }
        .category-pill { padding: 6px 14px; border: 1.5px solid #e2e8f0; background: #fff; color: var(--secondary); border-radius: 30px; font-size: 0.82rem; font-weight: 500; cursor: pointer; font-family: inherit; transition: var(--transition); white-space: nowrap; line-height: 1.4; }
        .category-pill:hover { border-color: var(--accent); color: var(--accent); background: #fff9f7; }
        .category-pill.active { background: var(--accent); border-color: var(--accent); color: #fff; }
        /* Loading state */
        #productGrid { transition: opacity 0.18s ease; }
        #productGrid.loading { opacity: 0.35; pointer-events: none; }
        .skeleton-card { background: #f1f5f9; border-radius: 12px; height: 300px; animation: shimmer 1.4s infinite; }
        @keyframes shimmer { 0%,100% { opacity: 0.6; } 50% { opacity: 1; } }
        /* Active filter chip */
        .active-filter-bar { margin-bottom: 18px; padding: 10px 16px; background: #fff8f5; border: 1px solid #fed7c7; border-radius: 8px; font-size: 0.86rem; color: var(--secondary); display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .reset-btn { color: var(--accent); font-weight: 600; background: none; border: none; cursor: pointer; font-family: inherit; font-size: 0.86rem; white-space: nowrap; display: flex; align-items: center; gap: 4px; }

        /* MAIN LAYOUT */
        .products-section { padding: 40px 0 60px; }

        /* PRODUCT GRID */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; margin-bottom: 50px; }

        /* PRODUCT CARD */
        .product-card { background-color: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: var(--transition); border: 1px solid #f1f5f9; cursor: pointer; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-color: #e2e8f0; }
        .product-img-wrapper { position: relative; height: 210px; background-color: #f1f5f9; overflow: hidden; }
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

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 80px 20px; grid-column: 1 / -1; }
        .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; display: block; }
        .empty-state h3 { font-size: 1.3rem; color: var(--primary); margin-bottom: 10px; }
        .empty-state p { color: var(--secondary); margin-bottom: 25px; }
        .empty-state a { color: var(--accent); text-decoration: underline; }

        /* PAGINATION */
        .pagination-wrapper { display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pagination-wrapper a, .pagination-wrapper span { display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; border-radius: 8px; font-size: 0.88rem; font-weight: 500; transition: var(--transition); border: 1.5px solid #e2e8f0; color: var(--secondary); background: #fff; text-decoration: none; }
        .pagination-wrapper a:hover { border-color: var(--accent); color: var(--accent); background: #fff9f7; }
        .pagination-wrapper span.active-page { background: var(--accent); border-color: var(--accent); color: #fff; }
        .pagination-wrapper span.dots { border-color: transparent; background: transparent; cursor: default; }
        .pagination-wrapper span.disabled { opacity: 0.4; cursor: not-allowed; }
        .pagination-info { text-align: center; margin-top: 16px; font-size: 0.85rem; color: var(--secondary); }

        /* MODAL */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.8); backdrop-filter: blur(5px); opacity: 0; transition: opacity 0.3s ease; }
        .modal.show { display: flex; align-items: center; justify-content: center; opacity: 1; }
        .modal-content { background-color: var(--card-bg); border-radius: 14px; width: 90%; max-width: 820px; max-height: 92vh; overflow-y: auto; position: relative; transform: translateY(-20px); transition: transform 0.3s ease; }
        .modal.show .modal-content { transform: translateY(0); }
        .close-modal { position: absolute; right: 18px; top: 14px; font-size: 26px; font-weight: bold; color: var(--secondary); cursor: pointer; z-index: 10; transition: var(--transition); line-height: 1; }
        .close-modal:hover { color: var(--accent); }
        .modal-body-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
        .modal-img-wrap { background: #f1f5f9; border-radius: 14px 0 0 14px; overflow: hidden; min-height: 300px; display: flex; align-items: center; }
        .modal-img { width: 100%; height: 100%; object-fit: cover; min-height: 300px; }
        .modal-details { padding: 35px 35px 35px 30px; display: flex; flex-direction: column; }
        .modal-details .badge { display: inline-block; background: var(--primary); color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; margin-bottom: 12px; align-self: flex-start; }
        .modal-details h2 { color: var(--primary); font-size: 1.7rem; line-height: 1.3; margin-bottom: 12px; }
        .modal-details p { color: var(--secondary); margin-bottom: 20px; font-size: 0.95rem; flex-grow: 1; }
        .modal-specs { background: var(--light-bg); padding: 14px 16px; border-radius: 8px; margin-bottom: 25px; }
        .modal-specs div { display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding: 8px 0; font-size: 0.88rem; }
        .modal-specs div:last-child { border: none; }
        .modal-specs strong { color: var(--primary); font-weight: 600; }

        /* FOOTER */
        footer { background-color: #020617; color: #64748b; padding: 28px 0; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; font-size: 0.88rem; }
        footer span { color: var(--accent); }

        /* FLOAT WA */
        .float-wa { position: fixed; width: 56px; height: 56px; bottom: 28px; right: 28px; background-color: #25d366; color: #FFF; border-radius: 50%; text-align: center; font-size: 26px; box-shadow: 0 4px 15px rgba(37,211,102,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; animation: pulse-wa 2s infinite; }
        .float-wa:hover { background-color: #128C7E; transform: scale(1.1); color: #fff; }
        @keyframes pulse-wa { 0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.7); } 70% { box-shadow: 0 0 0 15px rgba(37,211,102,0); } 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); } }

        @media (max-width: 992px) {
            .modal-body-grid { grid-template-columns: 1fr; }
            .modal-img-wrap { border-radius: 14px 14px 0 0; min-height: 200px; }
            .modal-img { min-height: 200px; max-height: 240px; }
            .modal-details { padding: 22px; }
        }
        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .nav-menu { display: none; }
            .search-filter-bar { top: 72px; }
            .page-header { padding: 36px 0 28px; }
            .page-header h1 { font-size: 1.7rem; }
            .search-row { gap: 8px; }
            .result-count { font-size: 0.8rem; }
            .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }
            .products-section { padding: 28px 0 48px; }
        }
        @media (max-width: 480px) {
            .container { padding: 0 14px; }
            .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .product-img-wrapper { height: 160px; }
            .product-info { padding: 12px 14px 12px; }
            .product-title { font-size: 0.9rem; }
            .product-desc { -webkit-line-clamp: 2; font-size: 0.78rem; margin-bottom: 10px; }
            .page-header h1 { font-size: 1.4rem; }
            .modal-details h2 { font-size: 1.25rem; }
            .modal-details { padding: 16px; }
            .search-filter-bar { position: static; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                <i class="fa-solid {{ $s['company_logo_icon'] ?? 'fa-gear' }}"></i>
                {{ explode(' ', $s['company_name'] ?? 'Company')[0] }}
                <span>{{ implode(' ', array_slice(explode(' ', $s['company_name'] ?? ''), 1)) }}</span>
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

    {{-- PAGE HEADER --}}
    <section class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span style="color: #fff;">Katalog Produk</span>
            </div>
            <h1>Katalog Produk</h1>
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
            </div>

            {{-- Row 2: Category Pills (wraps automatically) --}}
            <div class="pills-row" id="categoryPills">
                <button class="category-pill {{ !request('category') ? 'active' : '' }}" data-cat="">
                    Semua
                </button>
                @foreach($categories as $cat)
                    <button class="category-pill {{ request('category') == $cat->id ? 'active' : '' }}" data-cat="{{ $cat->id }}">
                        {{ $cat->title }}
                    </button>
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
                    <div class="product-card" onclick="openModal({{ $p->id }})">
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
                            <h3 class="product-title">{{ $p->title }}</h3>
                            <p class="product-desc">{{ $p->desc }}</p>
                            <div class="product-footer">
                                <div class="product-spec">
                                    {{ $p->spec_label }}:
                                    <span>{{ $p->spec_val }}</span>
                                </div>
                                <div class="card-actions">
                                    <button class="btn-icon btn-icon-wa"
                                        onclick="event.stopPropagation(); window.open('https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+tertarik+dengan+{{ urlencode($p->title) }}', '_blank')"
                                        title="Tanya via WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </button>
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

    {{-- MODAL PRODUK --}}
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+produk+Anda" class="float-wa" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <script>
        // ── CONFIG
        const SEARCH_URL = "{{ route('products.search') }}";
        const WA_NUMBER  = "{{ $s['wa_number'] ?? '' }}";
        const DEFAULT_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';

        // ── STATE
        let currentProducts = @json($productsJson); // initial from server
        let state = {
            q:        "{{ request('q', '') }}",
            category: "{{ request('category', '') }}",
            page:     {{ request('page', 1) }},
        };

        // ── ELEMENTS
        const searchInput  = document.getElementById('searchInput');
        const clearBtn     = document.getElementById('clearSearch');
        const grid         = document.getElementById('productGrid');
        const resultCount  = document.getElementById('resultCount');
        const filterBar    = document.getElementById('activeFilterBar');
        const filterText   = document.getElementById('activeFilterText');
        const pagWrapper   = document.getElementById('paginationWrapper');
        const pagInfo      = document.getElementById('paginationInfo');
        const modal        = document.getElementById('productModal');

        // ── INITIAL RENDER (dari data server)
        renderGrid(currentProducts);
        renderPagination({
            total:       {{ $products->total() }},
            currentPage: {{ $products->currentPage() }},
            lastPage:    {{ $products->lastPage() }},
            from:        {{ $products->firstItem() ?? 0 }},
            to:          {{ $products->lastItem() ?? 0 }},
        });

        // ── SEARCH INPUT
        searchInput.addEventListener('input', debounce(function () {
            state.q    = this.value.trim();
            state.page = 1;
            clearBtn.style.display = state.q ? 'flex' : 'none';
            fetchProducts();
        }, 380));

        clearBtn.addEventListener('click', function () {
            searchInput.value  = '';
            state.q            = '';
            state.page         = 1;
            this.style.display = 'none';
            fetchProducts();
            searchInput.focus();
        });

        // ── CATEGORY PILLS
        document.getElementById('categoryPills').addEventListener('click', function (e) {
            const pill = e.target.closest('.category-pill');
            if (!pill) return;
            document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            state.category = pill.dataset.cat;
            state.page     = 1;
            fetchProducts();
        });

        // ── FETCH
        async function fetchProducts(pushState = true) {
            grid.classList.add('loading');

            const params = new URLSearchParams();
            if (state.q)        params.set('q', state.q);
            if (state.category) params.set('category', state.category);
            if (state.page > 1) params.set('page', state.page);

            try {
                const res  = await fetch(`${SEARCH_URL}?${params}`);
                const data = await res.json();

                currentProducts = data.products;
                renderGrid(data.products);
                renderPagination(data);
                resultCount.innerHTML = `<strong>${data.total}</strong> produk`;
                updateFilterBar();

                if (pushState) {
                    const url = new URL(window.location.href);
                    ['q','category','page'].forEach(k => url.searchParams.delete(k));
                    if (state.q)        url.searchParams.set('q', state.q);
                    if (state.category) url.searchParams.set('category', state.category);
                    if (state.page > 1) url.searchParams.set('page', state.page);
                    history.pushState({ ...state }, '', url);
                }
            } catch (err) {
                console.error('Fetch error:', err);
            } finally {
                grid.classList.remove('loading');
            }
        }

        // ── RENDER GRID
        function renderGrid(products) {
            if (!products.length) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>Produk tidak ditemukan</h3>
                        <p>Coba ubah kata kunci atau pilih kategori lain. <button onclick="resetFilter()" style="color:var(--accent);background:none;border:none;cursor:pointer;font-weight:600;font-family:inherit;">Reset filter</button></p>
                    </div>`;
                return;
            }
            grid.innerHTML = products.map(p => `
                <div class="product-card" onclick="openModal(${p.id})">
                    <div class="product-img-wrapper">
                        ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}
                        ${p.categoryTitle ? `<span class="product-category-tag">${p.categoryTitle}</span>` : ''}
                        <img src="${p.img || DEFAULT_IMG}" alt="${p.title}" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-meta">${p.meta}</div>
                        <h3 class="product-title">${p.title}</h3>
                        <p class="product-desc">${p.desc}</p>
                        <div class="product-footer">
                            <div class="product-spec">${p.specLabel}:<span>${p.specVal}</span></div>
                            <div class="card-actions">
                                <button class="btn-icon btn-icon-wa" data-title="${p.title.replace(/"/g,'&quot;')}"
                                    onclick="event.stopPropagation();openWA(this.dataset.title)"
                                    title="Tanya via WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </button>
                                <button class="btn-icon btn-icon-info" title="Detail Produk"
                                    onclick="event.stopPropagation();openModal(${p.id})">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`).join('');
        }

        // ── RENDER PAGINATION
        function renderPagination(data) {
            if (data.lastPage <= 1) { pagWrapper.innerHTML = ''; pagInfo.textContent = ''; return; }

            let html = '';
            // Prev
            html += data.currentPage > 1
                ? `<a href="#" onclick="goPage(${data.currentPage - 1});return false;"><i class="fa-solid fa-chevron-left"></i></a>`
                : `<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>`;

            // Pages
            for (let p = 1; p <= data.lastPage; p++) {
                if (p === data.currentPage) {
                    html += `<span class="active-page">${p}</span>`;
                } else if (p === 1 || p === data.lastPage || Math.abs(p - data.currentPage) <= 2) {
                    html += `<a href="#" onclick="goPage(${p});return false;">${p}</a>`;
                } else if (Math.abs(p - data.currentPage) === 3) {
                    html += `<span class="dots">…</span>`;
                }
            }

            // Next
            html += data.currentPage < data.lastPage
                ? `<a href="#" onclick="goPage(${data.currentPage + 1});return false;"><i class="fa-solid fa-chevron-right"></i></a>`
                : `<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>`;

            pagWrapper.innerHTML = html;
            pagInfo.textContent  = data.from && data.to
                ? `Menampilkan ${data.from}–${data.to} dari ${data.total} produk`
                : '';
        }

        // ── ACTIVE FILTER BAR
        function updateFilterBar() {
            if (!state.q && !state.category) {
                filterBar.style.display = 'none';
                return;
            }
            let text = 'Menampilkan';
            if (state.q) text += ` untuk "<strong>${state.q}</strong>"`;
            if (state.category) {
                const name = document.querySelector(`.category-pill[data-cat="${state.category}"]`)?.textContent.trim();
                if (name) text += ` dalam <strong>${name}</strong>`;
            }
            filterText.innerHTML = text;
            filterBar.style.display = 'flex';
        }

        // ── HELPERS
        function goPage(page) {
            state.page = page;
            fetchProducts();
            window.scrollTo({ top: grid.offsetTop - 120, behavior: 'smooth' });
        }

        window.resetFilter = function () {
            searchInput.value      = '';
            clearBtn.style.display = 'none';
            state = { q: '', category: '', page: 1 };
            document.querySelectorAll('.category-pill').forEach((p, i) => p.classList.toggle('active', i === 0));
            fetchProducts();
        };

        function openWA(title) {
            window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent('Halo, saya tertarik dengan ' + title)}`, '_blank');
        }

        function debounce(fn, delay) {
            let timer;
            return function (...args) { clearTimeout(timer); timer = setTimeout(() => fn.apply(this, args), delay); };
        }

        // ── MODAL
        window.openModal = function (id) {
            const prod = currentProducts.find(p => p.id === id);
            if (!prod) return;
            document.getElementById('modal-body').innerHTML = `
                <div class="modal-body-grid">
                    <div class="modal-img-wrap">
                        <img src="${prod.img || DEFAULT_IMG}" alt="${prod.title}" class="modal-img">
                    </div>
                    <div class="modal-details">
                        <span class="badge">${prod.meta}</span>
                        <h2>${prod.title}</h2>
                        <p>${prod.desc}</p>
                        <div class="modal-specs">
                            <div><span>${prod.specLabel}</span> <strong>${prod.specVal}</strong></div>
                            <div><span>Kecepatan Maks (RPM)</span> <strong>${prod.rpm}</strong></div>
                            <div><span>Material Aplikasi</span> <strong>${prod.material}</strong></div>
                        </div>
                        <button onclick="openWA('${prod.title.replace(/'/g, "\\'")}')"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:#25d366;color:#fff;padding:13px;border-radius:8px;border:none;cursor:pointer;font-size:0.95rem;font-weight:600;font-family:inherit;transition:background 0.3s"
                            onmouseover="this.style.background='#1da851'" onmouseout="this.style.background='#25d366'">
                            <i class="fa-brands fa-whatsapp"></i> Pesan via WhatsApp
                        </button>
                    </div>
                </div>`;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        };

        const closeModal = () => { modal.classList.remove('show'); document.body.style.overflow = ''; };
        document.querySelector('.close-modal').onclick = closeModal;
        window.onclick = e => { if (e.target === modal) closeModal(); };
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── MOBILE NAV
        function toggleMobileNav() {
            document.getElementById('mobileNav').classList.toggle('open');
            document.getElementById('hamburger').classList.toggle('open');
        }

        // ── BROWSER BACK/FORWARD
        window.addEventListener('popstate', e => {
            if (e.state) {
                state = e.state;
                searchInput.value = state.q || '';
                clearBtn.style.display = state.q ? 'flex' : 'none';
                document.querySelectorAll('.category-pill').forEach(p => {
                    p.classList.toggle('active', p.dataset.cat === (state.category || ''));
                });
                fetchProducts(false);
            }
        });
    </script>
</body>
</html>
