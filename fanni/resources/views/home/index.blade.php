@php $show = fn($k) => ($s[$k] ?? '1') !== '0'; $hasWa = !empty($s['wa_number']); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('home._seo', ['page' => ['canonical' => route('home'), 'org' => true]])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

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
                    <a href="#contact" class="btn-outline" style="color:#fff;border-color:#fff;">{{ $s['hero_btn_secondary'] ?? 'Minta Penawaran' }}</a>
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
                        <h3>{{ $cat->title }}</h3>
                        <span class="category-meta">{{ $cat->products_count }} produk</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- PRODUK --}}
    @if($show('show_products'))
    <section id="products" class="container section-padding" style="padding-bottom: 40px;">
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
        <p id="product-empty" style="{{ $products->isEmpty() ? '' : 'display:none; ' }}text-align:center; color:#666;">Belum ada produk di kategori ini.</p>
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('products.index') }}" class="btn-primary" style="display: inline-flex;">
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

    {{-- MODAL ARTIKEL --}}
    <div id="blogModal" class="modal">
        <div class="modal-content blog-modal-content">
            <span class="close-modal" id="closeBlogModal">&times;</span>
            <div id="blogModalBody"></div>
        </div>
    </div>

    {{-- VIDEO --}}
    @if($show('show_video') && $videos->count())
    <section class="container section-padding" style="padding-top: 0;">
        <div class="section-header" style="margin-bottom: 30px;">
            <h2 style="font-size: 2rem;">{{ $s['video_title'] ?? 'Video' }}</h2>
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
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+meminta+Katalog+Produk" target="_blank" rel="noopener" class="btn-outline" style="color: #fff; border-color: #fff;"><i class="fa-brands fa-whatsapp"></i> Minta via WhatsApp</a>
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
    <section class="container section-padding" style="padding-top: 0;">
        <div class="section-header" style="margin-bottom: 40px;">
            <h2 style="font-size: 2rem;">{{ $s['testimonials_title'] ?? 'Apa Kata Mereka?' }}</h2>
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
                        <div class="faq-answer"><p style="padding-bottom: 20px;">{{ $faq->answer }}</p></div>
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
                <div class="blog-card" onclick="openBlogModal({{ $blog->id }})">
                    <div class="blog-img" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=400&q=80' }}')"></div>
                    <div class="blog-content">
                        <div class="blog-meta">{{ $blog->meta }}</div>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->excerpt(100) }}</p>
                        <a href="javascript:void(0)" class="read-more">Baca Artikel <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
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
                    <iframe src="{{ $s['maps_embed'] }}" title="Lokasi {{ $s['company_name'] ?? '' }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            style="width:100%; height:220px; border:0; border-radius:12px; margin-top:6px;"></iframe>
                    @endif
                </div>
                <div class="contact-form">
                    @if(session('contact_success'))
                        <div style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 14px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                            {{ session('contact_success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div style="position:absolute; left:-9999px;" aria-hidden="true">
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
    <script>
        const categoriesData = @json($categoriesJson);

        let currentFilter = 'all';

        function renderFilterButtons() {
            const container = document.getElementById('filter-container');
            if (!container) return;
            let html = `<button class="filter-btn active" data-filter="all">Semua Produk</button>`;
            categoriesData.forEach(cat => {
                html += `<button class="filter-btn" data-filter="${escapeHtml(cat.id)}">${escapeHtml(cat.title)}</button>`;
            });
            container.innerHTML = html;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    e.currentTarget.classList.add('active');
                    currentFilter = e.currentTarget.getAttribute('data-filter');
                    renderProducts(currentFilter);
                });
            });
        }

        function triggerFilter(type) {
            document.querySelector('#products').scrollIntoView();
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active');
                if(b.getAttribute('data-filter') === String(type)) b.classList.add('active');
            });
            currentFilter = type;
            renderProducts(type);
        }

        // Kartu produk dirender server-side; filter hanya menampilkan/menyembunyikan

        // ── TAMPILAN THUMBNAIL / DAFTAR (disimpan di browser; default thumbnail)
        function setProdView(mode) {
            document.getElementById('product-container').classList.toggle('list-view', mode === 'list');
            document.querySelectorAll('.vt-btn').forEach(b => b.classList.toggle('active', b.dataset.view === mode));
            try { localStorage.setItem('prodView', mode); } catch (e) {}
        }
        (function () { let m = 'grid'; try { m = localStorage.getItem('prodView') === 'list' ? 'list' : 'grid'; } catch (e) {} setProdView(m); })();

        function renderProducts(filterType) {
            const cards = document.querySelectorAll('#product-container .product-card');
            let visible = 0;
            cards.forEach(card => {
                const show = filterType === 'all' || card.dataset.type === String(filterType);
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            const empty = document.getElementById('product-empty');
            if (empty) empty.style.display = visible ? 'none' : 'block';
        }

        // ── VIDEO MODAL (desktop: tampil lebih besar) ──
        @php
            $videosData = $videos->map(fn($v) => ['id' => (string) $v->id, 'title' => $v->title]);
            $blogsData  = $blogs->map(fn($b) => ['id' => $b->id, 'meta' => $b->meta, 'title' => $b->title, 'desc' => $b->excerpt(200), 'html' => $b->body_html, 'img' => $b->img ? asset('storage/'.$b->img) : '']);
        @endphp
        const videosData  = @json($videosData);
        const videoModal  = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoModalPlayer');
        window.openVideoModal = function(id) {
            const v = videosData.find(x => x.id === String(id));
            videoPlayer.innerHTML = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" title="${v ? escapeAttr(v.title) : ''}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
            document.getElementById('videoModalTitle').textContent = v ? v.title : '';
            videoModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        };
        function closeVideoModal() { videoModal.classList.remove('show'); videoPlayer.innerHTML = ''; document.body.style.overflow = ''; }
        document.getElementById('closeVideoModal').onclick = closeVideoModal;

        // ── BLOG MODAL (baca artikel) ──
        const blogsData = @json($blogsData);
        const blogModal = document.getElementById('blogModal');
        window.openBlogModal = function(id) {
            const b = blogsData.find(x => x.id === id);
            if (!b) return;
            const img = b.img || 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1000&q=80';
            document.getElementById('blogModalBody').innerHTML = `
                <img src="${img}" alt="${escapeAttr(b.title)}" class="article-hero">
                <div class="article-body-wrap">
                    ${b.meta ? `<span class="badge">${escapeHtml(b.meta)}</span>` : ''}
                    <h2>${escapeHtml(b.title)}</h2>
                    <div class="article-content rich-content">${b.html}</div>
                </div>`;
            blogModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        };
        function closeBlogModal() { blogModal.classList.remove('show'); document.body.style.overflow = ''; }
        document.getElementById('closeBlogModal').onclick = closeBlogModal;

        function escapeHtml(s) { return String(s ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])); }
        function escapeAttr(s) { return escapeHtml(s); }

        // ── CLOSE HANDLERS (klik luar + Escape) untuk semua modal ──
        window.onclick = (e) => {
            if (e.target == videoModal) { closeVideoModal(); }
            if (e.target == blogModal)  { closeBlogModal(); }
        };
        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            if (videoModal.classList.contains('show')) closeVideoModal();
            if (blogModal.classList.contains('show'))  closeBlogModal();
            document.body.style.overflow = '';
        });

        function toggleMobileNav() {
            var nav = document.getElementById('mobileNav');
            var btn = document.getElementById('hamburger');
            var backdrop = document.getElementById('navBackdrop');
            var isOpen = nav.classList.contains('open');
            if (isOpen) {
                nav.classList.remove('open');
                btn.classList.remove('open');
                backdrop.classList.remove('open');
                document.body.style.overflow = '';
            } else {
                nav.classList.add('open');
                btn.classList.add('open');
                backdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeMobileNav() {
            document.getElementById('mobileNav').classList.remove('open');
            document.getElementById('hamburger').classList.remove('open');
            document.getElementById('navBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
        // Tutup menu saat scroll cukup jauh (bukan setiap pixel — hindari false trigger di iOS)
        var _navScrollY = 0;
        window.addEventListener('scroll', function() {
            if (document.getElementById('mobileNav').classList.contains('open')) {
                if (Math.abs(window.scrollY - _navScrollY) > 60) closeMobileNav();
            } else {
                _navScrollY = window.scrollY;
            }
        }, { passive: true });

        window.toggleFaq = function(element) {
            const parent = element.parentElement;
            const wasActive = parent.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
            if(!wasActive) parent.classList.add('active');
        }

        // Milestone counter animation
        const counters = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    const inc = target / 200;
                    const updateCount = () => {
                        const count = +counter.innerText;
                        if(count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => counterObserver.observe(c));

        document.addEventListener('DOMContentLoaded', () => {
            renderFilterButtons();
        });
    </script>
</body>
</html>
