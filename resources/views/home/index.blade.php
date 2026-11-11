@php $show = fn($k) => ($s[$k] ?? '1') !== '0'; $hasWa = !empty($s['wa_number']); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('home._seo', ['page' => ['canonical' => route('home'), 'org' => true, 'faq' => $show('show_faq') ? $faqs : []]])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <link rel="stylesheet" href="{{ asset('css/home-base.css') }}?v={{ filemtime(public_path('css/home-base.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/home-desktop.css') }}?v={{ filemtime(public_path('css/home-desktop.css')) }}">
</head>
<body>

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="#home" class="logo">
                @include('home._brand')
            </a>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link">Tentang</a></li>
                <li><a href="#products" class="nav-link">Produk</a></li>
                <li><a href="{{ route('blog.index') }}" class="nav-link">Blog</a></li>
                <li><a href="#clients" class="nav-link">Klien</a></li>
                <li><a href="#faq" class="nav-link">FAQ</a></li>
                <li><a href="#contact" class="nav-link">Kontak</a></li>
            </ul>
            <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMobileNav()">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <div class="nav-backdrop" id="navBackdrop" onclick="closeMobileNav()"></div>
    <nav class="mobile-nav" id="mobileNav">
        <a href="#home"     onclick="closeMobileNav()"><i class="fa-solid fa-house"></i> Beranda</a>
        <a href="{{ route('about') }}"><i class="fa-solid fa-circle-info"></i> Tentang</a>
        <a href="#products" onclick="closeMobileNav()"><i class="fa-solid fa-box-open"></i> Produk</a>
        <a href="{{ route('blog.index') }}"><i class="fa-solid fa-newspaper"></i> Blog</a>
        <a href="#clients"  onclick="closeMobileNav()"><i class="fa-solid fa-building-user"></i> Klien</a>
        <a href="#faq"      onclick="closeMobileNav()"><i class="fa-solid fa-circle-question"></i> FAQ</a>
        <a href="#contact"  onclick="closeMobileNav()"><i class="fa-solid fa-envelope"></i> Kontak</a>
    </nav>

    {{-- HERO --}}
    <section id="home" class="hero" @if(!empty($s['hero_bg'])) style="background-image: linear-gradient(rgba(15,23,42,0.82), rgba(15,23,42,0.82)), url('{{ Storage::url($s['hero_bg']) }}');" @endif>
        <div class="container">
            <div class="hero-content">
                <h1>{!! $s['hero_title'] ?? '' !!}</h1>
                <p>{!! $s['hero_subtitle'] ?? '' !!}</p>
                <div class="hero-buttons">
                    <a href="#products" class="btn-primary">{{ $s['hero_btn_primary'] ?? 'Jelajahi Produk' }} <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="#contact" class="btn-outline btn-outline-light">{{ $s['hero_btn_secondary'] ?? 'Minta Penawaran' }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- MILESTONE --}}
    @if($show('show_milestone'))
    <section class="milestone-section">
        <div class="container milestone-grid">
            <div class="milestone-card">
                <i class="fa-solid fa-calendar-check"></i>
                <h3><span class="counter" data-target="{{ $s['milestone_years'] ?? 0 }}">0</span>+</h3>
                <p>Tahun Pengalaman</p>
            </div>
            <div class="milestone-card">
                <i class="fa-solid fa-building-user"></i>
                <h3><span class="counter" data-target="{{ $s['milestone_clients'] ?? 0 }}">0</span>+</h3>
                <p>Klien Industri Aktif</p>
            </div>
            <div class="milestone-card">
                <i class="fa-solid fa-box-open"></i>
                <h3><span class="counter" data-target="{{ $s['milestone_products'] ?? 0 }}">0</span>+</h3>
                <p>Varian Produk</p>
            </div>
            <div class="milestone-card">
                <i class="fa-solid fa-truck-fast"></i>
                <h3><span class="counter" data-target="{{ $s['milestone_delivery'] ?? 0 }}">0</span>%</h3>
                <p>Pengiriman Tepat Waktu</p>
            </div>
        </div>
    </section>
    @endif

    {{-- KATEGORI --}}
    @if($show('show_categories') && $categories->count())
    @php $catIcons = ['amplas-lembaran', 'amplas-roll-belt', 'flap-disc-wheel', 'gerinda-potong', 'poles-buffing', 'scouring-pad', 'mata-bor-fastener', 'pengelasan-pneumatik', 'tape-perekat', 'pelumas-cleaner', 'perlengkapan-kerja']; @endphp
    @include('home._category-icons')
    <section id="categories" class="categories section-padding">
        <div class="container">
            <div class="section-header">
                <h2>{{ $s['categories_title'] ?? 'Kategori Produk' }}</h2>
                <p>{{ $s['categories_desc'] ?? '' }}</p>
            </div>
            <div class="category-grid">
                @foreach($categories as $cat)
                    <div class="category-card" onclick="triggerFilter('{{ $cat->id }}')" title="{{ $cat->title }}">
                        <div class="category-icon">
                            @if(in_array($cat->id, $catIcons))
                                <svg class="ci" aria-hidden="true"><use href="#ci-{{ $cat->id }}"></use></svg>
                            @else
                                <i class="fa-solid {{ $cat->icon ?: 'fa-layer-group' }}"></i>
                            @endif
                        </div>
                        <h3><a href="{{ route('products.index', ['category' => $cat->id]) }}" onclick="return false">{{ $cat->title }}</a></h3>
                        <span class="category-meta">{{ $cat->products_count }} produk</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- PRODUK --}}
    @if($show('show_products'))
    <section id="products" class="container section-padding pb-40">
        <div class="section-header">
            <h2>{{ $s['products_title'] ?? 'Katalog Produk' }}</h2>
            <p>{{ $s['products_desc'] ?? '' }}</p>
        </div>
        <div class="filter-container" id="filter-container"></div>
        <div class="view-bar-d">
            <div class="view-toggle" role="group" aria-label="Tampilan produk">
                    <button type="button" class="vt-btn" data-view="grid" onclick="setProdView('grid')" aria-label="Tampilan thumbnail" title="Thumbnail"><i class="fa-solid fa-table-cells-large"></i></button>
                    <button type="button" class="vt-btn" data-view="list" onclick="setProdView('list')" aria-label="Tampilan daftar" title="Daftar"><i class="fa-solid fa-list"></i></button>
                </div>
        </div>
        <div id="product-container" class="dynamic-grid">
            @foreach($products as $prod)
                <div class="product-card" data-type="{{ $prod['type'] }}" onclick="location.href='{{ $prod['url'] }}'">
                    <div class="product-img-wrapper">
                        @if($prod['badge'])<span class="product-badge">{{ $prod['badge'] }}</span>@endif
                        <img src="{{ $prod['img'] ?: 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-meta">{{ $prod['meta'] }}</div>
                        <h3 class="product-title"><a href="{{ $prod['url'] }}">{{ $prod['title'] }}</a></h3>
                        <p class="product-desc">{{ $prod['desc'] }}</p>
                        <div class="product-footer">
                            <div class="product-spec">{{ $prod['specLabel'] }}:<span>{{ $prod['specVal'] }}</span></div>
                            @if($hasWa)
                            <a class="btn-sm" href="https://wa.me/{{ $s['wa_number'] }}?text=Halo%2C+saya+tertarik+dengan+{{ urlencode($prod['title']) }}" target="_blank" rel="noopener" aria-label="Tanya {{ $prod['title'] }} via WhatsApp" onclick="event.stopPropagation()"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <p id="product-empty" class="empty-note" @if($products->isNotEmpty()) hidden @endif>Belum ada produk di kategori ini.</p>
        <div class="center-block mt-50">
            <a href="{{ route('products.index') }}" id="allProductsLink" class="btn-primary">
                Lihat Semua Produk <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>
    @endif

    {{-- MODAL VIDEO --}}
    <div id="videoModal" class="modal video-modal">
        <div class="modal-content video-modal-content">
            <span class="close-modal" id="closeVideoModal">&times;</span>
            <div class="video-modal-frame" id="videoModalPlayer"></div>
            <div class="video-modal-caption"><h3 id="videoModalTitle"></h3></div>
        </div>
    </div>

    {{-- VIDEO --}}
    @if($show('show_video') && $videos->count())
    <section class="container section-padding pt-0">
        <div class="section-header mb-30">
            <h2 class="h2-lg">{{ $s['video_title'] ?? 'Video' }}</h2>
            <p>{{ $s['video_desc'] ?? '' }}</p>
        </div>
        <div class="dynamic-grid">
            @foreach($videos as $video)
                <div class="video-card" onclick="openVideoModal('{{ $video->id }}')">
                    <div class="video-wrapper" style="background-image: url('https://img.youtube.com/vi/{{ $video->id }}/hqdefault.jpg');">
                        <button class="video-play" aria-label="Putar video"><i class="fa-solid fa-play"></i></button>
                    </div>
                    <div class="video-info">
                        <h3>{{ $video->title }}</h3>
                        <p>{{ $video->desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA BANNER --}}
    @if($show('show_cta'))
    <section class="container">
        <div class="cta-banner">
            <h2>{{ $s['cta_title'] ?? 'Butuh Spesifikasi Teknis Lengkap?' }}</h2>
            <p>{{ strip_tags($s['cta_desc'] ?? '') }}</p>
            <div class="cta-buttons">
                @if(!empty($s['ecatalog_file']))
                    <a href="{{ Storage::url($s['ecatalog_file']) }}" target="_blank" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog (PDF)</a>
                @else
                    <a href="#contact" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog (PDF)</a>
                @endif
                @if($hasWa)
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+meminta+Katalog+Produk" target="_blank" rel="noopener" class="btn-outline btn-outline-light"><i class="fa-brands fa-whatsapp"></i> Minta via WhatsApp</a>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- KLIEN --}}
    @if($show('show_clients') && $clients->count())
    <section id="clients" class="clients-section section-padding">
        <div class="section-header">
            <h2>{{ $s['clients_title'] ?? 'Klien Kami' }}</h2>
            <p>{{ $s['clients_desc'] ?? '' }}</p>
        </div>
        <div class="marquee-container">
            <div class="marquee-track">
                @php $tripled = $clients->concat($clients)->concat($clients); @endphp
                @foreach($tripled as $client)
                    <div class="client-logo">
                        @if($client->logo)
                            <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" loading="lazy">
                        @endif
                        <span>{{ $client->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- TESTIMONI --}}
    @if($show('show_testimonials') && $testimonials->count())
    <section class="container section-padding pt-0">
        <div class="section-header mb-40">
            <h2 class="h2-lg">{{ $s['testimonials_title'] ?? 'Apa Kata Mereka?' }}</h2>
        </div>
        <div class="dynamic-grid">
            @foreach($testimonials as $t)
                <div class="testimonial-card">
                    <p class="testi-text">"{{ $t->text }}"</p>
                    <div class="testi-author">
                        <div class="testi-avatar">{{ strtoupper(substr($t->author, 0, 1)) }}</div>
                        <div class="testi-info">
                            <h4>{{ $t->author }}</h4>
                            <p>{{ $t->pos }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if($show('show_faq') && $faqs->count())
    <section id="faq" class="faq-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2>{{ $s['faq_title'] ?? 'FAQ' }}</h2>
                <p>{{ $s['faq_desc'] ?? '' }}</p>
            </div>
            <div class="faq-container">
                @foreach($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            {{ $faq->question }}
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer"><p>{{ $faq->answer }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- BLOG --}}
    @if($show('show_blog') && $blogs->count())
    <section class="container section-padding">
        <div class="section-header">
            <h2>{{ $s['blog_title'] ?? 'Blog' }}</h2>
            <p>{{ $s['blog_desc'] ?? '' }}</p>
        </div>
        <div class="dynamic-grid">
            @foreach($blogs as $blog)
                <div class="blog-card" onclick="location.href='{{ route('blog.show', $blog) }}'">
                    <div class="blog-img" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=400&q=80' }}')"></div>
                    <div class="blog-content">
                        <div class="blog-meta">{{ $blog->meta }}</div>
                        <h3><a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a></h3>
                        <p>{{ $blog->excerpt(100) }}</p>
                        <a href="{{ route('blog.show', $blog) }}" class="read-more">Baca Artikel <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
        @if($blogsTotal > $blogs->count())
        <div class="center-block mt-40">
            <a href="{{ route('blog.index') }}" class="btn-outline">
                Lihat Semua Artikel ({{ $blogsTotal }}) <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </section>
    @endif

    {{-- KONTAK --}}
    @if($show('show_contact'))
    <section id="contact" class="contact section-padding">
        <div class="container">
            <div class="section-header">
                <h2>{{ $s['contact_title'] ?? 'Hubungi Kami' }}</h2>
                <p>{{ strip_tags($s['contact_desc'] ?? '') }}</p>
            </div>
            <div class="contact-grid">
                <div class="contact-details">
                    <h3>Pusat Penjualan</h3>
                    @if(!empty($s['address']))
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="contact-text">
                            <h4>{{ $s['company_full_name'] ?? '' }}</h4>
                            <p>{!! nl2br(e($s['address'])) !!}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($s['phone']))
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-text">
                            <h4>Telepon</h4>
                            <p>{{ $s['phone'] }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($s['email']))
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-text">
                            <h4>Email Resmi</h4>
                            <p>{{ $s['email'] }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($s['business_hours']))
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="contact-text">
                            <h4>Jam Operasional</h4>
                            <p>{{ $s['business_hours'] }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($s['maps_embed']))
                    <iframe src="{{ $s['maps_embed'] }}" title="Lokasi {{ $s['company_name'] ?? '' }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map-embed"></iframe>
                    @endif
                </div>
                <div class="contact-form">
                    @if(session('contact_success'))
                        <div class="alert-ok">
                            {{ session('contact_success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="hp-field" aria-hidden="true">
                            <label>Jangan diisi <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap / Nama Perusahaan" required>
                            @error('name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Alamat Email" required>
                            @error('email')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Nomor Telepon/WhatsApp" required>
                            @error('phone')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <select name="subject">
                                <option value="">Pilih keperluan (opsional)</option>
                                @foreach(\App\Models\ContactMessage::SUBJECTS as $opt)
                                    <option value="{{ $opt }}" @selected(old('subject', $prefill['subject'] ?? '') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @error('subject')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="4" placeholder="Detail kebutuhan Anda..." required>{{ old('message', $prefill['message'] ?? '') }}</textarea>
                            @error('message')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn-primary">Kirim Permintaan <i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif

    <footer>
        <div class="container">
            @include('home._social')
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    @if($hasWa)
    <a href="https://wa.me/{{ $s['wa_number'] }}?text=Halo%2C+saya+ingin+bertanya+tentang+produk+Anda" class="float-wa" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    @endif

    {{-- DATA PRODUK & KATEGORI UNTUK JAVASCRIPT --}}
    @php
        $categoriesJson = $categories->map(fn($c) => [
            'id'    => $c->id,
            'title' => $c->title,
            'desc'  => $c->desc,
            'img'   => $c->img ? asset('storage/'.$c->img) : 'https://images.unsplash.com/photo-1513828583835-c5417eb91e1d?auto=format&fit=crop&w=400&q=80',
        ]);
    @endphp
    @php
        $videosData = $videos->map(fn($v) => ['id' => (string) $v->id, 'title' => $v->title]);
    @endphp
    <script>
        window.PAGE = {
            categories: @json($categoriesJson),
            searchUrl: @json(route('products.search', [], false)),
            waNumber: @json($s['wa_number'] ?? ''),
            allProductsUrl: @json(route('products.index')),
            videos: @json($videosData),
        };
    </script>
    <script src="{{ asset('js/home-desktop.js') }}?v={{ filemtime(public_path('js/home-desktop.js')) }}"></script>
</body>
</html>
