@php $show = fn($k) => ($s[$k] ?? '1') !== '0'; $hasWa = !empty($s['wa_number']); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
    @include('home._seo', ['page' => ['canonical' => route('home'), 'org' => true, 'faq' => $show('show_faq') ? $faqs : []]])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <link rel="stylesheet" href="{{ asset('css/home-mobile.css') }}?v={{ filemtime(public_path('css/home-mobile.css')) }}">
</head>
<body>

    {{-- ══════════ HEADER ══════════ --}}
    <header class="app-header">
        <a class="app-logo" onclick="showScreen('home')">
                @include('home._brand')
            </a>
        <div class="header-actions">
            <button class="icon-btn" onclick="showScreen('products'); setTimeout(()=>document.getElementById('mSearch').focus(),350)" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if($hasWa)
            <a class="icon-btn wa" href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+produk+Anda" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            @endif
        </div>
    </header>

    <main class="app-main">

        {{-- ═══════════════════ SCREEN: BERANDA ═══════════════════ --}}
        <section class="screen active" id="screen-home">
            {{-- HERO --}}
            <div class="hero" @if(!empty($s['hero_bg'])) style="background: linear-gradient(rgba(15,23,42,0.35), rgba(15,23,42,0.9)), url('{{ Storage::url($s['hero_bg']) }}') center/cover no-repeat;" @endif>
                <div>
                    <span class="hero-eyebrow">{{ $s['company_tagline'] ?? 'Company Profile' }}</span>
                    <h1>{!! $s['hero_title'] ?? 'Solusi Terbaik untuk Bisnis Anda' !!}</h1>
                    <p>{!! $s['hero_subtitle'] ?? '' !!}</p>
                    <div class="hero-actions">
                        <button class="btn btn-primary" onclick="showScreen('products')">{{ $s['hero_btn_primary'] ?? 'Lihat Produk' }} <i class="fa-solid fa-arrow-right"></i></button>
                        <button class="btn btn-ghost" onclick="showScreen('contact')">{{ $s['hero_btn_secondary'] ?? 'Kontak' }}</button>
                    </div>
                </div>
            </div>

            {{-- MILESTONES --}}
            @if($show('show_milestone'))
            <div class="stats-strip">
                <div class="stat-box"><i class="fa-solid fa-calendar-check"></i><h3><span class="counter" data-target="{{ $s['milestone_years'] ?? 0 }}">0</span>+</h3><p>Tahun Pengalaman</p></div>
                <div class="stat-box"><i class="fa-solid fa-building-user"></i><h3><span class="counter" data-target="{{ $s['milestone_clients'] ?? 0 }}">0</span>+</h3><p>Klien Aktif</p></div>
                <div class="stat-box"><i class="fa-solid fa-box-open"></i><h3><span class="counter" data-target="{{ $s['milestone_products'] ?? 0 }}">0</span>+</h3><p>Varian Produk</p></div>
                <div class="stat-box"><i class="fa-solid fa-truck-fast"></i><h3><span class="counter" data-target="{{ $s['milestone_delivery'] ?? 0 }}">0</span>%</h3><p>Tepat Waktu</p></div>
            </div>
            @endif

            {{-- KATEGORI --}}
            @if($show('show_categories') && $categories->count())
            <div class="pad pad-b6">
                <div class="sec-title">{{ $s['categories_title'] ?? 'Kategori' }} <span class="cat-total">{{ $categories->count() }} kategori</span></div>
                <div class="sec-sub">{{ $s['categories_desc'] ?? 'Jelajahi kategori produk kami' }}</div>
            </div>
            @php $catIcons = ['amplas-lembaran', 'amplas-roll-belt', 'flap-disc-wheel', 'gerinda-potong', 'poles-buffing', 'scouring-pad', 'mata-bor-fastener', 'pengelasan-pneumatik', 'tape-perekat', 'pelumas-cleaner', 'perlengkapan-kerja']; @endphp
            @include('home._category-icons')
            <div class="cat-list">
                @foreach($categories as $cat)
                    <a class="cat-row" href="{{ route('products.index', ['category' => $cat->id]) }}" onclick="goCategory('{{ $cat->id }}'); return false;">
                        <span class="cat-ico">
                            @if(in_array($cat->id, $catIcons))
                                <svg class="ci" aria-hidden="true"><use href="#ci-{{ $cat->id }}"></use></svg>
                            @else
                                <i class="fa-solid {{ $cat->icon ?: 'fa-layer-group' }}"></i>
                            @endif
                        </span>
                        <span class="cat-info">
                            <strong>{{ $cat->title }}</strong>
                            <small>{{ $cat->products_count }} produk</small>
                        </span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @endforeach
            </div>
            @endif

            {{-- PRODUK UNGGULAN --}}
            @if($show('show_products'))
            <div class="pad pad-b6">
                <div class="sec-title">{{ $s['products_title'] ?? 'Produk Unggulan' }}
                    <a class="link-more" href="{{ route('products.index') }}" onclick="showScreen('products'); return false;">Semua <i class="fa-solid fa-chevron-right i-xs"></i></a>
                </div>
                <div class="sec-sub">{{ $s['products_desc'] ?? '' }}</div>
            </div>
            <div class="view-bar">
                <span class="view-count" id="homeCount">Menampilkan {{ $products->count() }} dari {{ $productsTotal }} produk</span>
                <div class="view-toggle" role="group" aria-label="Tampilan produk">
                    <button type="button" class="vt-btn" data-view="grid" onclick="setProdView('grid')" aria-label="Tampilan kotak"><i class="fa-solid fa-table-cells-large"></i></button>
                    <button type="button" class="vt-btn" data-view="list" onclick="setProdView('list')" aria-label="Tampilan daftar"><i class="fa-solid fa-list"></i></button>
                </div>
            </div>
            <div class="prod-grid" id="featured-grid">
                @foreach($products as $p)
                <div class="prod-card" onclick="location.href='{{ $p['url'] }}'">
                    <div class="prod-img">
                        @if($p['badge'])<span class="prod-badge">{{ $p['badge'] }}</span>@endif
                        <img src="{{ $p['img'] ?: 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $p['title'] }}" loading="lazy">
                    </div>
                    <div class="prod-body">
                        <span class="prod-meta">{{ $p['meta'] }}</span>
                        <h4 class="prod-title"><a href="{{ $p['url'] }}">{{ $p['title'] }}</a></h4>
                        <div class="prod-foot">
                            <div class="prod-spec">{{ $p['specLabel'] }}<b>{{ $p['specVal'] ?: '-' }}</b></div>
                            @if($hasWa)
                            <button class="prod-wa" onclick="event.stopPropagation(); waProduct('{{ rawurlencode($p['title']) }}')" aria-label="Tanya {{ $p['title'] }} via WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @if($products->isEmpty())<div class="empty-note">Belum ada produk.</div>@endif
            </div>
            @if($productsTotal > $products->count())
            <div class="load-more" id="homeLoadMore">
                <button type="button" class="btn btn-ghost btn-block btn-ghost-accent" onclick="showScreen('products')"><span>Muat Lebih Banyak</span> <i class="fa-solid fa-arrow-right" style="font-size:0.75rem;margin-left:6px;"></i></button>
            </div>
            @endif
            @endif

            {{-- CTA --}}
            @if($show('show_cta'))
            <div class="cta">
                <h3>{{ $s['cta_title'] ?? 'Butuh Spesifikasi Lengkap?' }}</h3>
                <p>{{ strip_tags($s['cta_desc'] ?? 'Dapatkan katalog produk lengkap kami.') }}</p>
                @if(!empty($s['ecatalog_file']))
                    <a href="{{ Storage::url($s['ecatalog_file']) }}" target="_blank" class="btn btn-primary btn-block"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog</a>
                @endif
                @if($hasWa)
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+meminta+Katalog+Produk" target="_blank" rel="noopener" class="btn btn-wa btn-block"><i class="fa-brands fa-whatsapp"></i> Minta via WhatsApp</a>
                @endif
            </div>
            @endif

            {{-- KLIEN --}}
            @if($show('show_clients') && $clients->count())
            <div class="pad pad-b4">
                <div class="sec-title">{{ $s['clients_title'] ?? 'Klien Kami' }}</div>
                <div class="sec-sub">{{ $s['clients_desc'] ?? '' }}</div>
            </div>
            <div class="marquee">
                <div class="marquee-track">
                    @php $tripled = $clients->concat($clients)->concat($clients); @endphp
                    @foreach($tripled as $client)
                        <div class="m-logo">
                            @if($client->logo)<img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" loading="lazy">@endif
                            <span>{{ $client->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- TESTIMONI --}}
            @if($show('show_testimonials') && $testimonials->count())
            <div class="pad pad-b4">
                <div class="sec-title">{{ $s['testimonials_title'] ?? 'Apa Kata Mereka?' }}</div>
            </div>
            <div class="rail">
                @foreach($testimonials as $t)
                    <div class="testi-card">
                        <div class="quote"><i class="fa-solid fa-quote-left"></i></div>
                        <p>{{ $t->text }}</p>
                        <div class="testi-author">
                            <div class="testi-av">{{ strtoupper(substr($t->author, 0, 1)) }}</div>
                            <div><h5>{{ $t->author }}</h5><small>{{ $t->pos }}</small></div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- VIDEO --}}
            @if($show('show_video') && $videos->count())
            <div class="pad pad-b4">
                <div class="sec-title">{{ $s['video_title'] ?? 'Video' }}</div>
                <div class="sec-sub">{{ $s['video_desc'] ?? '' }}</div>
            </div>
            <div class="pad pad-t4">
                @foreach($videos as $video)
                    <div class="video-card">
                        <div class="video-frame" style="background-image: url('https://img.youtube.com/vi/{{ $video->id }}/hqdefault.jpg');" onclick="playVideo('{{ $video->id }}')">
                            <button class="video-play" aria-label="Putar video"><i class="fa-solid fa-play"></i></button>
                        </div>
                        <div class="vi"><h4>{{ $video->title }}</h4><p>{{ $video->desc }}</p></div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- FAQ --}}
            @if($show('show_faq') && $faqs->count())
            <div class="pad pad-b8">
                <div class="sec-title">{{ $s['faq_title'] ?? 'FAQ' }}</div>
                <div class="sec-sub">{{ $s['faq_desc'] ?? '' }}</div>
                @foreach($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">{{ $faq->question }} <i class="fa-solid fa-chevron-down"></i></div>
                        <div class="faq-a"><p>{{ $faq->answer }}</p></div>
                    </div>
                @endforeach
            </div>
            @endif

            @if($show('show_blog') && $blogs->count())
            <div class="pad pad-b6">
                <div class="sec-title">{{ $s['blog_title'] ?? 'Artikel Terbaru' }}</div>
            </div>
            <div class="pad pad-t6">
                @foreach($blogs as $blog)
                    <div class="blog-card" onclick="location.href='{{ route('blog.show', $blog) }}'">
                        <div class="blog-thumb" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=300&q=80' }}');"></div>
                        <div class="blog-info">
                            <span class="bm">{{ $blog->meta }}</span>
                            <h4><a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a></h4>
                            <p>{{ $blog->excerpt(80) }}</p>
                        </div>
                    </div>
                @endforeach
                @if($blogsTotal > $blogs->count())
                <a href="{{ route('blog.index') }}" class="btn btn-ghost btn-block btn-ghost-accent">Lihat Semua Artikel ({{ $blogsTotal }})</a>
                @endif
            </div>
            @endif

            @include('home._social')
            <div class="footer-note">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
        </section>

        {{-- ═══════════════════ SCREEN: PRODUK ═══════════════════ --}}
        <section class="screen" id="screen-products">
            <div class="search-wrap">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="mSearch" placeholder="Cari produk..." autocomplete="off">
                    <i class="fa-solid fa-xmark" id="clearSearch" onclick="clearSearch()"></i>
                </div>
            </div>
            <div class="chips" id="chips">
                <button class="chip active" data-cat="all">Semua</button>
                @foreach($categories as $cat)
                    <button class="chip" data-cat="{{ $cat->id }}">{{ $cat->title }}</button>
                @endforeach
            </div>
            <div class="view-bar">
                <span class="view-count" id="prodCount"></span>
                <div class="view-toggle" role="group" aria-label="Tampilan produk">
                    <button type="button" class="vt-btn" data-view="grid" onclick="setProdView('grid')" aria-label="Tampilan kotak"><i class="fa-solid fa-table-cells-large"></i></button>
                    <button type="button" class="vt-btn" data-view="list" onclick="setProdView('list')" aria-label="Tampilan daftar"><i class="fa-solid fa-list"></i></button>
                </div>
            </div>
            <div class="prod-grid" id="prodResults"></div>
            <div id="loadMoreWrap" class="load-more">
                <button class="btn btn-ghost btn-block btn-ghost-accent" onclick="loadMore()">Muat Lebih Banyak</button>
            </div>
        </section>

        {{-- ═══════════════════ SCREEN: TENTANG ═══════════════════ --}}
        <section class="screen" id="screen-about">
            @include('home._m_about', ['standalone' => false])

            @include('home._social')
            <div class="footer-note">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
        </section>

        {{-- ═══════════════════ SCREEN: KONTAK ═══════════════════ --}}
        <section class="screen" id="screen-contact">
            <div class="pad">
                <div class="sec-title">{{ $s['contact_title'] ?? 'Hubungi Kami' }}</div>
                <div class="sec-sub">{{ strip_tags($s['contact_desc'] ?? 'Kami siap membantu kebutuhan Anda') }}</div>

                @if(!empty($s['address']))
                <div class="contact-card">
                    <div class="contact-ic"><i class="fa-solid fa-location-dot"></i></div>
                    <div><h5>{{ $s['company_full_name'] ?? 'Alamat' }}</h5><p>{!! nl2br(e($s['address'])) !!}</p></div>
                </div>
                @endif
                @if(!empty($s['phone']))
                <a class="contact-card" href="tel:{{ $s['phone'] }}">
                    <div class="contact-ic"><i class="fa-solid fa-phone"></i></div>
                    <div><h5>Telepon</h5><p>{{ $s['phone'] }}</p></div>
                </a>
                @endif
                @if(!empty($s['email']))
                <a class="contact-card" href="mailto:{{ $s['email'] }}">
                    <div class="contact-ic"><i class="fa-solid fa-envelope"></i></div>
                    <div><h5>Email Resmi</h5><p>{{ $s['email'] }}</p></div>
                </a>
                @endif
                @if(!empty($s['business_hours']))
                <div class="contact-card">
                    <div class="contact-ic"><i class="fa-regular fa-clock"></i></div>
                    <div><h5>Jam Operasional</h5><p>{{ $s['business_hours'] }}</p></div>
                </div>
                @endif
                @if(!empty($s['maps_embed']))
                <iframe src="{{ $s['maps_embed'] }}" title="Lokasi" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map-embed"></iframe>
                @endif

                @if($hasWa)
                <a class="btn btn-wa btn-block btn-wa-gap" href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+berkonsultasi" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Chat WhatsApp Sekarang</a>
                @endif

                <div class="sec-title sec-title--sm">Kirim Pesan</div>
                @if(session('contact_success'))
                    <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> {{ session('contact_success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="hp-field" aria-hidden="true">
                        <label>Jangan diisi <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <div class="form-field">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama / Perusahaan" required>
                        @error('name')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Alamat Email" required>
                        @error('email')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="No. Telepon / WhatsApp" required>
                        @error('phone')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <select name="subject">
                            <option value="">Pilih keperluan (opsional)</option>
                            @foreach(\App\Models\ContactMessage::SUBJECTS as $opt)
                                <option value="{{ $opt }}" @selected(old('subject', $prefill['subject'] ?? '') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('subject')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <textarea name="message" rows="4" placeholder="Detail kebutuhan Anda..." required>{{ old('message', $prefill['message'] ?? '') }}</textarea>
                        @error('message')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Kirim Permintaan <i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
            @include('home._social')
            <div class="footer-note">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
        </section>
    </main>

    {{-- ══════════ BOTTOM TAB BAR ══════════ --}}
    <nav class="tabbar">
        <button class="tab active" data-screen="home"     onclick="showScreen('home')"><i class="fa-solid fa-house"></i>Beranda</button>
        <button class="tab"        data-screen="products" onclick="showScreen('products')"><i class="fa-solid fa-box-open"></i>Produk</button>
        <button class="tab"        data-screen="about"    onclick="showScreen('about')"><i class="fa-solid fa-circle-info"></i>Tentang</button>
        <button class="tab"        data-screen="contact"  onclick="showScreen('contact')"><i class="fa-solid fa-headset"></i>Kontak</button>
    </nav>

    {{-- ══════════ VIDEO FULLSCREEN OVERLAY ══════════ --}}
    <div class="video-fs" id="videoFs">
        <button class="video-fs-close" onclick="closeVideo()" aria-label="Tutup video"><i class="fa-solid fa-xmark"></i></button>
        <div class="video-fs-inner" id="videoFsInner"></div>
    </div>

    <div class="toast" id="toast"></div>

    {{-- ══════════ DATA + LOGIC ══════════ --}}
    @php
        $categoriesJson = $categories->map(fn($c) => [
            'id' => $c->id, 'title' => $c->title,
        ]);
    @endphp
    <script>
        window.PAGE = {
            products: @json($products),
            categories: @json($categoriesJson),
            waNumber: @json($s['wa_number'] ?? ''),
            searchUrl: @json(route('products.search', [], false)),
            openContact: @json(!empty($prefill) || $errors->any() || session('contact_success')),
        };
    </script>
    <script src="{{ asset('js/home-mobile.js') }}?v={{ filemtime(public_path('js/home-mobile.js')) }}"></script>
</body>
</html>
