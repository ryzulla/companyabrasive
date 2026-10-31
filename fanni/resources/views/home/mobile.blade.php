@php $show = fn($k) => ($s[$k] ?? '1') !== '0'; $hasWa = !empty($s['wa_number']); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
    @include('home._seo', ['page' => ['canonical' => route('home'), 'org' => true]])

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
            @if(!empty($s['phone']))
            <a class="icon-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $s['phone']) }}" aria-label="Telepon"><i class="fa-solid fa-phone"></i></a>
            @endif
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
            <div class="pad" style="padding-bottom: 6px;">
                <div class="sec-title">{{ $s['categories_title'] ?? 'Kategori' }} <span class="cat-total">{{ $categories->count() }} kategori</span></div>
                <div class="sec-sub">{{ $s['categories_desc'] ?? 'Jelajahi kategori produk kami' }}</div>
            </div>
            @php $catIcons = ['amplas-lembaran', 'amplas-roll-belt', 'flap-disc-wheel', 'gerinda-potong', 'poles-buffing', 'scouring-pad', 'mata-bor-fastener', 'pengelasan-pneumatik', 'tape-perekat', 'pelumas-cleaner', 'perlengkapan-kerja']; @endphp
            @include('home._category-icons')
            <div class="cat-list">
                @foreach($categories as $cat)
                    <button type="button" class="cat-row" onclick="goCategory('{{ $cat->id }}')">
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
                    </button>
                @endforeach
            </div>
            @endif

            {{-- PRODUK UNGGULAN --}}
            @if($show('show_products'))
            <div class="pad" style="padding-bottom: 6px;">
                <div class="sec-title">{{ $s['products_title'] ?? 'Produk Unggulan' }}
                    <a class="link-more" onclick="showScreen('products')">Semua <i class="fa-solid fa-chevron-right" style="font-size:0.62rem;"></i></a>
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
                @if($products->isEmpty())<div style="grid-column:1/-1;padding:0 4px;color:#94a3b8;font-size:0.85rem;">Belum ada produk.</div>@endif
            </div>
            @if($productsTotal > $products->count())
            <div class="load-more" id="homeLoadMore">
                <button type="button" class="btn btn-ghost btn-block" style="color:var(--accent);border-color:var(--accent);" onclick="showScreen('products')"><span>Muat Lebih Banyak</span> <i class="fa-solid fa-arrow-right" style="font-size:0.75rem;margin-left:6px;"></i></button>
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
            <div class="pad" style="padding-bottom: 4px;">
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
            <div class="pad" style="padding-bottom: 4px;">
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
            <div class="pad" style="padding-bottom: 4px;">
                <div class="sec-title">{{ $s['video_title'] ?? 'Video' }}</div>
                <div class="sec-sub">{{ $s['video_desc'] ?? '' }}</div>
            </div>
            <div class="pad" style="padding-top: 4px;">
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
            <div class="pad" style="padding-bottom: 8px;">
                <div class="sec-title">{{ $s['faq_title'] ?? 'FAQ' }}</div>
                <div class="sec-sub">{{ $s['faq_desc'] ?? '' }}</div>
                @foreach($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-q" onclick="toggleFaq(this)">{{ $faq->question }} <i class="fa-solid fa-chevron-down"></i></div>
                        <div class="faq-a"><p style="padding-top:2px;">{{ $faq->answer }}</p></div>
                    </div>
                @endforeach
            </div>
            @endif

            @if($show('show_blog') && $blogs->count())
            <div class="pad" style="padding-bottom: 6px;">
                <div class="sec-title">{{ $s['blog_title'] ?? 'Artikel Terbaru' }}</div>
            </div>
            <div class="pad" style="padding-top: 6px;">
                @foreach($blogs as $blog)
                    <div class="blog-card" onclick="openBlogSheet({{ $blog->id }})">
                        <div class="blog-thumb" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=300&q=80' }}');"></div>
                        <div class="blog-info">
                            <span class="bm">{{ $blog->meta }}</span>
                            <h4>{{ $blog->title }}</h4>
                            <p>{{ $blog->excerpt(80) }}</p>
                        </div>
                    </div>
                @endforeach
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
                    <i class="fa-solid fa-xmark" id="clearSearch" style="display:none;cursor:pointer;" onclick="clearSearch()"></i>
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
            <div id="loadMoreWrap" class="load-more" style="display:none;">
                <button class="btn btn-ghost btn-block" style="color:var(--accent);border-color:var(--accent);" onclick="loadMore()">Muat Lebih Banyak</button>
            </div>
        </section>

        {{-- ═══════════════════ SCREEN: TENTANG ═══════════════════ --}}
        <section class="screen" id="screen-about">
            <div class="pad">
                <div class="about-img">
                    <img src="{{ !empty($s['about_img']) ? Storage::url($s['about_img']) : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80' }}" alt="Tentang Kami">
                </div>
                <div class="about-text">
                    <h3>{{ $s['about_title'] ?? 'Tentang Kami' }}</h3>
                    <p>{!! $s['about_desc'] ?? '' !!}</p>
                    <div class="feat-list">
                        @foreach([1,2,3,4] as $i)
                            @if(!empty($s['about_feature_'.$i]))
                                <div class="feat-item"><i class="fa-solid fa-circle-check"></i> {{ $s['about_feature_'.$i] }}</div>
                            @endif
                        @endforeach
                    </div>
                    <div class="certs">
                        @foreach([1,2,3] as $i)
                            @if(!empty($s['about_cert_'.$i]))
                                <span class="cert"><i class="fa-solid {{ $s['about_cert_'.$i.'_icon'] ?? 'fa-certificate' }}"></i> {{ $s['about_cert_'.$i] }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="stats-strip" style="margin: 0 16px; border-radius: 18px;">
                <div class="stat-box"><i class="fa-solid fa-calendar-check"></i><h3><span class="counter" data-target="{{ $s['milestone_years'] ?? 0 }}">0</span>+</h3><p>Tahun Pengalaman</p></div>
                <div class="stat-box"><i class="fa-solid fa-building-user"></i><h3><span class="counter" data-target="{{ $s['milestone_clients'] ?? 0 }}">0</span>+</h3><p>Klien Aktif</p></div>
                <div class="stat-box"><i class="fa-solid fa-box-open"></i><h3><span class="counter" data-target="{{ $s['milestone_products'] ?? 0 }}">0</span>+</h3><p>Varian Produk</p></div>
                <div class="stat-box"><i class="fa-solid fa-truck-fast"></i><h3><span class="counter" data-target="{{ $s['milestone_delivery'] ?? 0 }}">0</span>%</h3><p>Tepat Waktu</p></div>
            </div>

            {{-- CTA KONTAK --}}
            <div class="pad" style="padding-top: 18px;">
                <button class="btn btn-primary btn-block" style="margin-bottom: 10px;" onclick="showScreen('contact')"><i class="fa-solid fa-paper-plane"></i> Hubungi Kami</button>
                @if($hasWa)
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+perusahaan+Anda" target="_blank" rel="noopener" class="btn btn-wa btn-block"><i class="fa-brands fa-whatsapp"></i> Chat via WhatsApp</a>
                @endif
            </div>

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
                <iframe src="{{ $s['maps_embed'] }}" title="Lokasi" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        style="width:100%; height:200px; border:0; border-radius:14px; margin:4px 0 12px;"></iframe>
                @endif

                @if($hasWa)
                <a class="btn btn-wa btn-block" style="margin: 8px 0 22px;" href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+berkonsultasi" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Chat WhatsApp Sekarang</a>
                @endif

                <div class="sec-title" style="font-size:1rem;margin-bottom:14px;">Kirim Pesan</div>
                @if(session('contact_success'))
                    <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> {{ session('contact_success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div style="position:absolute; left:-9999px;" aria-hidden="true">
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

    {{-- ══════════ BOTTOM SHEET: DETAIL PRODUK ══════════ --}}
    <div class="sheet-backdrop" id="sheetBackdrop" onclick="closeSheet()"></div>
    <div class="sheet" id="prodSheet">
        <div class="sheet-handle"></div>
        <button class="sheet-close" onclick="closeSheet()"><i class="fa-solid fa-xmark"></i></button>
        <div id="sheetBody"></div>
    </div>

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
        const FEATURED   = @json($products);
        const CATEGORIES = @json($categoriesJson);
        const WA         = "{{ $s['wa_number'] ?? '' }}";
        const SEARCH_URL = "{{ route('products.search', [], false) }}";
        const FALLBACK_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';

        @if(!empty($prefill) || $errors->any() || session('contact_success'))
        document.addEventListener('DOMContentLoaded', () => showScreen('contact'));
        @endif

        /* ───── SCREEN NAV ───── */
        function showScreen(name) {
            document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
            document.getElementById('screen-' + name).classList.add('active');
            document.querySelectorAll('.tab').forEach(t => t.classList.toggle('active', t.dataset.screen === name));
            window.scrollTo({ top: 0, behavior: 'auto' });
            if (name === 'products' && !prodLoaded) loadProducts(true);
        }

        /* ───── PRODUCT CARD TEMPLATE ───── */
        function cardHTML(p) {
            return `<div class="prod-card" onclick="location.href='${p.url}'">
                <div class="prod-img">
                    ${p.badge ? `<span class="prod-badge">${p.badge}</span>` : ''}
                    <img src="${p.img || FALLBACK_IMG}" alt="${escapeHtml(p.title)}" loading="lazy">
                </div>
                <div class="prod-body">
                    <span class="prod-meta">${escapeHtml(p.meta || '')}</span>
                    <h4 class="prod-title">${escapeHtml(p.title)}</h4>
                    <div class="prod-foot">
                        <div class="prod-spec">${escapeHtml(p.specLabel || '')}<b>${escapeHtml(p.specVal || '-')}</b></div>
                        ${WA ? `<button class="prod-wa" onclick="event.stopPropagation(); waProduct('${encodeURIComponent(p.title)}')" aria-label="Tanya via WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></button>` : ''}
                    </div>
                </div>
            </div>`;
        }


        /* ───── TAMPILAN KOTAK / DAFTAR (disimpan di browser) ───── */
        function setProdView(mode) {
            document.querySelectorAll('.prod-grid').forEach(g => g.classList.toggle('list-view', mode === 'list'));
            document.querySelectorAll('.vt-btn').forEach(b => b.classList.toggle('active', b.dataset.view === mode));
            try { localStorage.setItem('prodView', mode); } catch (e) {}
        }
        (function () { let m = 'grid'; try { m = localStorage.getItem('prodView') === 'list' ? 'list' : 'grid'; } catch (e) {} setProdView(m); })();

        /* ───── MUAT LEBIH BANYAK: jangan sampai ketukan kedua membuka produk ─────
           Produk baru disisipkan di ATAS tombol, sehingga tombol turun jauh dan di titik jari tadi
           muncul kartu produk. Ketukan ulang di sana (atau double tap) akan membuka halaman produk.
           Solusinya: gulir ke produk baru, dan abaikan ketukan pada kartu sesaat setelah memuat. */
        let navGuardUntil = 0;
        function armClickGuard(ms = 900) { navGuardUntil = Date.now() + ms; }
        document.addEventListener('click', e => {
            if (Date.now() < navGuardUntil && e.target.closest('.prod-card')) { e.preventDefault(); e.stopPropagation(); }
        }, true);
        function revealNew(container, previousCount) {
            const first = container.querySelectorAll('.prod-card')[previousCount];
            if (!first) return;
            armClickGuard(700);
            const header = document.querySelector('.app-header');
            const offset = (header ? header.getBoundingClientRect().height : 0) + 12;
            window.scrollTo({ top: first.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
        }


        /* ───── PRODUCTS SCREEN (live fetch + filter + search) ───── */
        let curCat = 'all', curQuery = '', curPage = 1, lastPage = 1, prodLoaded = false, fetching = false, searchTimer = null;
        let _resultsCache = [];
        const resultsEl = document.getElementById('prodResults');
        const loadMoreWrap = document.getElementById('loadMoreWrap');

        function skeletons(n = 6) {
            resultsEl.innerHTML = Array.from({ length: n }, () => '<div class="skel"></div>').join('');
        }

        async function loadProducts(reset = true) {
            if (fetching) return;
            fetching = true;
            if (reset) { curPage = 1; skeletons(); }
            try {
                const url = new URL(SEARCH_URL, window.location.origin);
                if (curQuery) url.searchParams.set('q', curQuery);
                url.searchParams.set('category', curCat);
                url.searchParams.set('page', curPage);
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                lastPage = data.lastPage || 1;
                document.getElementById('prodCount').textContent = (data.total ?? 0) + ' produk';
                const items = data.products || [];
                if (reset) _resultsCache = items.slice();
                else _resultsCache.push(...items);
                const html = items.map(cardHTML).join('');
                if (reset) {
                    resultsEl.innerHTML = html || emptyState();
                } else {
                    const before = resultsEl.querySelectorAll('.prod-card').length;
                    resultsEl.insertAdjacentHTML('beforeend', html);
                    revealNew(resultsEl, before);
                }
                loadMoreWrap.style.display = curPage < lastPage ? 'block' : 'none';
                prodLoaded = true;
            } catch (e) {
                resultsEl.innerHTML = emptyState('Gagal memuat produk. Coba lagi.');
                loadMoreWrap.style.display = 'none';
            } finally {
                fetching = false;
            }
        }
        function loadMore() { armClickGuard(4000); curPage++; loadProducts(false); }
        function emptyState(msg) {
            return `<div class="empty-state" style="grid-column:1/-1;"><i class="fa-solid fa-box-open"></i><p>${msg || 'Belum ada produk di kategori ini.'}</p></div>`;
        }

        // chips
        document.getElementById('chips').addEventListener('click', e => {
            const btn = e.target.closest('.chip');
            if (!btn) return;
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            curCat = btn.dataset.cat;
            loadProducts(true);
        });

        // search (debounced)
        const searchInput = document.getElementById('mSearch');
        const clearBtn = document.getElementById('clearSearch');
        searchInput.addEventListener('input', () => {
            curQuery = searchInput.value.trim();
            clearBtn.style.display = curQuery ? 'block' : 'none';
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadProducts(true), 350);
        });
        function clearSearch() {
            searchInput.value = ''; curQuery = ''; clearBtn.style.display = 'none';
            loadProducts(true); searchInput.focus();
        }

        function goCategory(id) {
            showScreen('products');
            document.querySelectorAll('.chip').forEach(c => c.classList.toggle('active', c.dataset.cat == id));
            curCat = String(id);
            loadProducts(true);
        }

        /* ───── BOTTOM SHEET ───── */
        const sheet = document.getElementById('prodSheet');
        const sheetBackdrop = document.getElementById('sheetBackdrop');
        function showSheet() {
            sheetBackdrop.classList.add('open');
            sheet.classList.add('open');
            sheet.scrollTop = 0;
            document.body.style.overflow = 'hidden';
        }
        function closeSheet() {
            sheet.classList.remove('open');
            sheetBackdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        /* ───── BLOG SHEET (baca artikel) ───── */
        @php
            $blogsData = $blogs->map(fn($b) => ['id' => $b->id, 'meta' => $b->meta, 'title' => $b->title, 'desc' => $b->excerpt(200), 'html' => $b->body_html, 'img' => $b->img ? Storage::url($b->img) : '']);
        @endphp
        const BLOGS = @json($blogsData);
        const BLOG_FALLBACK = 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80';
        function openBlogSheet(id) {
            const b = BLOGS.find(x => x.id === id);
            if (!b) return;
            document.getElementById('sheetBody').innerHTML = `
                <img class="sheet-img" src="${b.img || BLOG_FALLBACK}" alt="${escapeHtml(b.title)}">
                <div class="sheet-content">
                    <span class="sheet-badge">${escapeHtml(b.meta || 'Artikel')}</span>
                    <h2>${escapeHtml(b.title)}</h2>
                    <div class="article-body rich-content">${b.html || ''}</div>
                </div>`;
            showSheet();
        }

        /* ───── VIDEO FULLSCREEN (mobile: langsung penuh layar) ───── */
        const videoFs = document.getElementById('videoFs');
        const videoFsInner = document.getElementById('videoFsInner');
        function playVideo(id) {
            videoFsInner.innerHTML = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0&playsinline=1&fs=1" title="Video" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
            videoFs.classList.add('open');
            document.body.style.overflow = 'hidden';
            const req = videoFs.requestFullscreen || videoFs.webkitRequestFullscreen;
            if (req) { try { const r = req.call(videoFs); if (r && r.catch) r.catch(() => {}); } catch (e) {} }
        }
        function closeVideo() {
            videoFs.classList.remove('open');
            videoFsInner.innerHTML = '';
            document.body.style.overflow = '';
            if (document.fullscreenElement) document.exitFullscreen().catch(() => {});
            else if (document.webkitFullscreenElement) document.webkitExitFullscreen();
        }
        // Tutup overlay bila user keluar dari fullscreen native
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && videoFs.classList.contains('open')) closeVideo();
        });
        window.addEventListener('keydown', e => { if (e.key === 'Escape' && videoFs.classList.contains('open')) closeVideo(); });

        /* ───── WHATSAPP ───── */
        function waProduct(titleEnc, verb = 'tertarik+dengan') {
            window.open(`https://wa.me/${WA}?text=Halo%2C+saya+${verb}+${titleEnc}`, '_blank');
        }

        /* ───── FAQ ───── */
        function toggleFaq(el) {
            const item = el.parentElement;
            const open = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!open) item.classList.add('open');
        }

        /* ───── COUNTER ───── */
        const counters = document.querySelectorAll('.counter');
        const co = new IntersectionObserver((entries, obs) => {
            entries.forEach(en => {
                if (!en.isIntersecting) return;
                const el = en.target, target = +el.dataset.target, inc = Math.max(1, target / 60);
                const tick = () => {
                    const v = +el.innerText;
                    if (v < target) { el.innerText = Math.ceil(v + inc); requestAnimationFrame(tick); }
                    else el.innerText = target;
                };
                tick(); obs.unobserve(el);
            });
        }, { threshold: 0.4 });
        counters.forEach(c => co.observe(c));

        /* ───── UTIL ───── */
        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
        }
        let toastTimer;
        function toast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg; t.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.remove('show'), 2200);
        }

        // close sheet on Escape / back gesture
        window.addEventListener('keydown', e => { if (e.key === 'Escape') closeSheet(); });

        /* ───── buka layar dari hash (#products, #about, #contact), mis. dari halaman detail produk ───── */
        (function () {
            var h = location.hash.replace('#', '');
            if (['products', 'about', 'contact'].indexOf(h) !== -1) showScreen(h);
        })();
    </script>
</body>
</html>
