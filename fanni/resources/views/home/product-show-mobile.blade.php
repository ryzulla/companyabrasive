@php $placeholder = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=800&q=80'; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
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
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->title, 'item' => $canonical],
            ],
        ];
    @endphp
    @include('home._seo', ['page' => [
        'title'       => $product->title . ' | ' . $company,
        'description' => $seoDesc,
        'canonical'   => $canonical,
        'image'       => $imgAbs->first(),
        'og_type'     => 'product',
        'jsonld'      => [$jsonLd, $breadcrumb],
    ]])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <link rel="stylesheet" href="{{ asset('css/home-mobile.css') }}?v={{ filemtime(public_path('css/home-mobile.css')) }}">
</head>
<body>

    @php $hasWa = !empty($s['wa_number']); @endphp

    {{-- ══════════ HEADER ══════════ --}}
    <header class="app-header detail">
        <a class="back-btn" href="{{ route('products.index') }}" onclick="if (history.length > 1 && document.referrer.indexOf(location.host) !== -1) { history.back(); return false; }" aria-label="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
        <a class="app-logo" href="{{ route('home') }}">
            @include('home._brand')
        </a>
        <div class="header-actions">
            @if(!empty($s['phone']))
            <a class="icon-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $s['phone']) }}" aria-label="Telepon"><i class="fa-solid fa-phone"></i></a>
            @endif
            @if($hasWa)
            <a class="icon-btn wa" href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya tertarik dengan '.$product->title) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            @endif
        </div>
    </header>

    <main class="app-main has-actionbar">

        {{-- GALERI --}}
        <div class="pd-gallery">
            <div class="pd-track" id="pdTrack">
                @forelse($images as $i => $url)
                    <div class="pd-slide" onclick="openLightbox({{ $i }})">
                        <img src="{{ $url }}" alt="{{ $product->title }}" @if($i > 0) loading="lazy" @endif>
                    </div>
                @empty
                    <div class="pd-slide"><img src="{{ $placeholder }}" alt="{{ $product->title }}"></div>
                @endforelse
            </div>
            @if($images->isNotEmpty())
                <span class="pd-zoom"><i class="fa-solid fa-magnifying-glass-plus"></i> Ketuk untuk zoom</span>
            @endif
            @if($images->count() > 1)
                <span class="pd-count" id="pdCount">1 / {{ $images->count() }}</span>
            @endif
        </div>
        @if($images->count() > 1)
            <div class="pd-dots" id="pdDots">
                @foreach($images as $i => $url)<span class="{{ $i === 0 ? 'active' : '' }}"></span>@endforeach
            </div>
        @endif

        {{-- INFO --}}
        <div class="pd-body">
            <div class="pd-tags">
                <span class="pd-tag">{{ $product->meta }}</span>
                @if($product->category)<span class="pd-tag cat">{{ $product->category->title }}</span>@endif
            </div>
            <h1 class="pd-title">{{ $product->title }}</h1>
            <p class="pd-desc">{{ $product->desc }}</p>

            <div class="pd-specs">
                <div><span>{{ $product->spec_label }}</span><strong>{{ $product->spec_val }}</strong></div>
                @if(filled($product->rpm))<div><span>Kecepatan Maks (RPM)</span><strong>{{ $product->rpm }}</strong></div>@endif
                <div><span>Material Aplikasi</span><strong>{{ $product->material }}</strong></div>
            </div>
        </div>

        {{-- PRODUK SERUPA --}}
        @if($related->isNotEmpty())
        <section class="pd-related">
            <div class="sec-title">Produk Serupa</div>
            <div class="rail">
                @foreach($related as $r)
                    <a href="{{ route('products.show', $r) }}" class="prod-card">
                        <div class="prod-img">
                            @if($r->badge)<span class="prod-badge">{{ $r->badge }}</span>@endif
                            <img src="{{ $r->img ? Storage::url($r->img) : $placeholder }}" alt="{{ $r->title }}" loading="lazy">
                        </div>
                        <div class="prod-body">
                            <span class="prod-meta">{{ $r->meta }}</span>
                            <h4 class="prod-title">{{ $r->title }}</h4>
                            <div class="prod-foot">
                                <div class="prod-spec">{{ $r->spec_label }}<b>{{ $r->spec_val ?: '-' }}</b></div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <div class="footer-note" style="padding: 22px 16px 6px;">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
    </main>

    {{-- BAR AKSI --}}
    <div class="pd-bar">
        <a class="btn btn-primary" href="{{ route('home', ['produk' => $product->slug]) }}#contact"><i class="fa-solid fa-file-invoice"></i> Minta Penawaran</a>
        @if($hasWa)
        <a class="btn btn-wa-icon" href="https://wa.me/{{ $s['wa_number'] }}?text={{ urlencode('Halo, saya ingin memesan '.$product->title) }}" target="_blank" rel="noopener" aria-label="Pesan via WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        @endif
    </div>

    {{-- TAB BAR (sama dengan beranda mobile) --}}
    <nav class="tabbar">
        <a class="tab" href="{{ route('home') }}"><i class="fa-solid fa-house"></i>Beranda</a>
        <a class="tab active" href="{{ route('home') }}#products"><i class="fa-solid fa-box-open"></i>Produk</a>
        <a class="tab" href="{{ route('home') }}#about"><i class="fa-solid fa-circle-info"></i>Tentang</a>
        <a class="tab" href="{{ route('home') }}#contact"><i class="fa-solid fa-headset"></i>Kontak</a>
    </nav>

    {{-- LIGHTBOX / ZOOM --}}
    <div class="lb" id="lightbox" aria-hidden="true">
        <div class="lb-bar">
            <span id="lbCount"></span>
            <div class="lb-tools">
                <button type="button" class="lb-btn" onclick="lbZoomBy(-0.5)" aria-label="Perkecil"><i class="fa-solid fa-minus"></i></button>
                <button type="button" class="lb-btn" onclick="lbZoomBy(0.5)" aria-label="Perbesar"><i class="fa-solid fa-plus"></i></button>
                <button type="button" class="lb-btn" onclick="closeLightbox()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <div class="lb-stage" id="lbStage">
            <img id="lbImg" src="" alt="{{ $product->title }}" draggable="false">
        </div>
        @if($images->count() > 1)
            <div class="lb-thumbs" id="lbThumbs">
                @foreach($images as $i => $url)
                    <button type="button" onclick="lbGo({{ $i }})" aria-label="Foto {{ $i + 1 }}"><img src="{{ $url }}" alt=""></button>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        const GALLERY = @json($images);
        let idx = 0;

        /* ───── CAROUSEL ───── */
        const track = document.getElementById('pdTrack');
        const dots = document.querySelectorAll('#pdDots span');
        const countEl = document.getElementById('pdCount');
        function setIdx(i) {
            idx = i;
            dots.forEach((d, n) => d.classList.toggle('active', n === i));
            if (countEl) countEl.textContent = (i + 1) + ' / ' + GALLERY.length;
        }
        track.addEventListener('scroll', () => {
            const i = Math.round(track.scrollLeft / track.clientWidth);
            if (i !== idx) setIdx(i);
        }, { passive: true });

        /* ───── LIGHTBOX + ZOOM ───── */
        const lb = document.getElementById('lightbox');
        const lbStage = document.getElementById('lbStage');
        const lbImg = document.getElementById('lbImg');
        const LB_MIN = 1, LB_MAX = 5;
        let z = { scale: 1, x: 0, y: 0 };
        const pointers = new Map();
        let dragStart = null, pinchStart = null, moved = false;

        function lbApply() { lbImg.style.transform = `translate(${z.x}px, ${z.y}px) scale(${z.scale})`; }
        function lbClamp() {
            const mx = Math.max(0, (lbImg.clientWidth * z.scale - lbStage.clientWidth) / 2);
            const my = Math.max(0, (lbImg.clientHeight * z.scale - lbStage.clientHeight) / 2);
            z.x = Math.min(mx, Math.max(-mx, z.x));
            z.y = Math.min(my, Math.max(-my, z.y));
        }
        function lbReset() { z = { scale: 1, x: 0, y: 0 }; lbApply(); }
        function lbSetScale(next, cx, cy) {
            next = Math.min(LB_MAX, Math.max(LB_MIN, next));
            if (cx !== undefined) {
                const r = lbStage.getBoundingClientRect();
                const px = cx - (r.left + r.width / 2), py = cy - (r.top + r.height / 2);
                const k = next / z.scale;
                z.x = px - (px - z.x) * k;
                z.y = py - (py - z.y) * k;
            }
            z.scale = next;
            if (next === 1) { z.x = 0; z.y = 0; }
            lbClamp(); lbApply();
        }
        function lbZoomBy(d) { lbSetScale(z.scale + d); }
        function lbSync() {
            lbImg.src = GALLERY[idx];
            document.getElementById('lbCount').textContent = GALLERY.length > 1 ? (idx + 1) + ' / ' + GALLERY.length : '';
            document.querySelectorAll('#lbThumbs button').forEach((b, n) => b.classList.toggle('active', n === idx));
            lbReset();
        }
        function lbGo(i) {
            idx = (i + GALLERY.length) % GALLERY.length;
            lbSync();
        }
        function openLightbox(i) {
            if (!GALLERY.length) return;
            idx = i || 0;
            lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            lbSync();
        }
        function closeLightbox() {
            lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            // samakan carousel dengan foto terakhir yang dilihat
            track.scrollTo({ left: idx * track.clientWidth, behavior: 'auto' });
            setIdx(idx);
        }

        lbStage.addEventListener('pointerdown', e => {
            lbStage.setPointerCapture(e.pointerId);
            pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
            moved = false;
            if (pointers.size === 1) dragStart = { x: e.clientX, y: e.clientY, ox: z.x, oy: z.y };
            if (pointers.size === 2) {
                const [a, b] = [...pointers.values()];
                pinchStart = { d: Math.hypot(a.x - b.x, a.y - b.y), scale: z.scale };
            }
        });
        lbStage.addEventListener('pointermove', e => {
            if (!pointers.has(e.pointerId)) return;
            pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
            if (pointers.size === 2 && pinchStart) {
                const [a, b] = [...pointers.values()];
                lbSetScale(pinchStart.scale * Math.hypot(a.x - b.x, a.y - b.y) / pinchStart.d, (a.x + b.x) / 2, (a.y + b.y) / 2);
                moved = true;
            } else if (pointers.size === 1 && dragStart) {
                const dx = e.clientX - dragStart.x, dy = e.clientY - dragStart.y;
                if (Math.abs(dx) + Math.abs(dy) > 4) moved = true;
                if (z.scale > 1) {
                    z.x = dragStart.ox + dx; z.y = dragStart.oy + dy;
                    lbClamp(); lbApply();
                }
            }
        });
        function lbPointerEnd(e) {
            const wasSingle = pointers.size === 1;
            const start = dragStart;
            pointers.delete(e.pointerId);
            if (pointers.size < 2) pinchStart = null;
            if (pointers.size === 0) {
                // geser horizontal saat belum di-zoom = pindah foto
                if (wasSingle && start && z.scale === 1 && e.type === 'pointerup') {
                    const dx = e.clientX - start.x;
                    if (Math.abs(dx) > 60 && Math.abs(e.clientY - start.y) < 60) { lbGo(idx + (dx < 0 ? 1 : -1)); dragStart = null; return; }
                }
                // ketuk tanpa geser = toggle zoom
                if (!moved && wasSingle && e.type === 'pointerup') lbSetScale(z.scale > 1 ? 1 : 2.5, e.clientX, e.clientY);
                dragStart = null;
            }
        }
        lbStage.addEventListener('pointerup', lbPointerEnd);
        lbStage.addEventListener('pointercancel', lbPointerEnd);
        window.addEventListener('keydown', e => { if (e.key === 'Escape' && lb.classList.contains('open')) closeLightbox(); });
    </script>
</body>
</html>
