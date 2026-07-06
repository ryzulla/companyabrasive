@php $show = fn($k) => ($s[$k] ?? '1') !== '0'; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0">
    <meta name="theme-color" content="#0f172a">
    <title>{{ $s['company_name'] ?? 'Company Profile' }} | {{ $s['company_tagline'] ?? '' }}</title>

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
            --border: #eef2f7;
            --header-h: 56px;
            --tab-h: 62px;
            --safe-top: env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
            --transition: all 0.25s ease;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--light-bg); color: var(--text-main); line-height: 1.55;
            overflow-x: hidden; font-size: 15px;
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }
        button { font-family: inherit; cursor: pointer; border: none; background: none; }
        ::-webkit-scrollbar { width: 0; height: 0; }

        /* ─────────── APP HEADER ─────────── */
        .app-header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            height: calc(var(--header-h) + var(--safe-top));
            padding-top: var(--safe-top);
            background: rgba(255,255,255,0.94); backdrop-filter: blur(14px);
            display: flex; align-items: center; justify-content: space-between;
            padding-left: 16px; padding-right: 12px;
            border-bottom: 1px solid var(--border);
        }
        .app-logo { display: flex; align-items: center; gap: 8px; font-size: 1.08rem; font-weight: 700; color: var(--primary); }
        .app-logo span { color: var(--accent); }
        .app-logo img { height: 30px; width: auto; object-fit: contain; }
        .header-actions { display: flex; align-items: center; gap: 4px; }
        .icon-btn {
            width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 1.05rem; transition: var(--transition);
        }
        .icon-btn:active { background: var(--light-bg); }
        .icon-btn.wa { color: #25d366; }

        /* ─────────── SCREEN SYSTEM ─────────── */
        .app-main {
            padding-top: calc(var(--header-h) + var(--safe-top));
            padding-bottom: calc(var(--tab-h) + var(--safe-bottom));
            min-height: 100vh; min-height: 100svh;
        }
        .screen { display: none; animation: fadeUp 0.32s ease; }
        .screen.active { display: block; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .pad { padding: 20px 16px; }
        .sec-title { font-size: 1.15rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
        .sec-sub { font-size: 0.82rem; color: var(--secondary); margin-bottom: 16px; }
        .link-more { font-size: 0.78rem; font-weight: 600; color: var(--accent); display: inline-flex; align-items: center; gap: 5px; }

        /* ─────────── HERO ─────────── */
        .hero {
            position: relative; min-height: 62vh; min-height: 62svh; display: flex; align-items: flex-end;
            color: #fff; padding: 28px 18px 34px;
            background: linear-gradient(rgba(15,23,42,0.35), rgba(15,23,42,0.9)),
                        url('https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
        }
        .hero-eyebrow { display: inline-block; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(234,88,12,0.9); color: #fff; padding: 5px 12px; border-radius: 30px; margin-bottom: 14px; }
        .hero h1 { font-size: 1.85rem; line-height: 1.22; font-weight: 700; margin-bottom: 12px; }
        .hero h1 span { color: var(--accent); }
        .hero p { font-size: 0.92rem; color: #e2e8f0; margin-bottom: 20px; max-width: 90%; }
        .hero-actions { display: flex; gap: 10px; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; transition: var(--transition); white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 6px 18px rgba(234,88,12,0.32); }
        .btn-ghost { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(6px); }
        .btn-block { width: 100%; }
        .btn-wa { background: #25d366; color: #fff; box-shadow: 0 6px 18px rgba(37,211,102,0.32); }

        /* ─────────── MILESTONES ─────────── */
        .stats-strip { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 18px 16px; background: var(--primary); }
        .stat-box { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 16px 12px; text-align: center; }
        .stat-box i { color: var(--accent); font-size: 1.15rem; margin-bottom: 8px; display: block; }
        .stat-box h3 { color: #fff; font-size: 1.5rem; font-weight: 700; line-height: 1; }
        .stat-box p { color: #94a3b8; font-size: 0.72rem; margin-top: 5px; }

        /* ─────────── HORIZONTAL RAILS ─────────── */
        .rail { display: flex; gap: 12px; overflow-x: auto; padding: 4px 16px 8px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
        .rail > * { scroll-snap-align: start; flex-shrink: 0; }

        /* Category card */
        .cat-card { width: 140px; height: 160px; border-radius: 16px; overflow: hidden; position: relative; box-shadow: 0 4px 14px rgba(15,23,42,0.1); }
        .cat-card .bg { position: absolute; inset: 0; background-size: cover; background-position: center; transition: transform 0.4s ease; }
        .cat-card:active .bg { transform: scale(1.06); }
        .cat-card .ov { position: absolute; inset: 0; background: linear-gradient(transparent 35%, rgba(15,23,42,0.9)); display: flex; flex-direction: column; justify-content: flex-end; padding: 12px; }
        .cat-card .ov h4 { color: #fff; font-size: 0.85rem; font-weight: 600; }
        .cat-card .ov p { color: #cbd5e1; font-size: 0.68rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ─────────── PRODUCT CARD ─────────── */
        .prod-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 4px 16px; }
        .prod-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 10px rgba(15,23,42,0.04); display: flex; flex-direction: column; transition: var(--transition); }
        .prod-card:active { transform: scale(0.98); }
        .rail .prod-card { width: 190px; }
        .prod-img { position: relative; height: 130px; background: #f1f5f9; overflow: hidden; }
        .prod-img img { width: 100%; height: 100%; object-fit: cover; }
        .prod-badge { position: absolute; top: 8px; left: 8px; background: var(--primary); color: #fff; font-size: 0.62rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.03em; }
        .prod-body { padding: 11px 12px 13px; flex-grow: 1; display: flex; flex-direction: column; }
        .prod-meta { font-size: 0.63rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.04em; }
        .prod-title { font-size: 0.86rem; font-weight: 600; color: var(--primary); margin: 3px 0 6px; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .prod-spec { font-size: 0.68rem; color: var(--secondary); margin-top: auto; }
        .prod-spec b { display: block; color: var(--primary); font-size: 0.78rem; }
        .prod-foot { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 10px; }
        .prod-wa { width: 32px; height: 32px; border-radius: 10px; background: var(--light-bg); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; transition: var(--transition); }
        .prod-wa:active { background: var(--accent); color: #fff; }

        /* ─────────── SEARCH BAR ─────────── */
        .search-wrap { position: sticky; top: calc(var(--header-h) + var(--safe-top)); z-index: 50; background: var(--light-bg); padding: 14px 16px 8px; }
        .search-box { display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 11px 14px; box-shadow: 0 2px 10px rgba(15,23,42,0.04); }
        .search-box i { color: var(--secondary); font-size: 0.9rem; }
        .search-box input { flex: 1; border: none; outline: none; font-size: 0.9rem; background: transparent; color: var(--text-main); }
        .chips { display: flex; gap: 8px; overflow-x: auto; padding: 10px 16px 4px; }
        .chip { flex-shrink: 0; padding: 7px 15px; border-radius: 30px; font-size: 0.78rem; font-weight: 600; background: #fff; border: 1.5px solid var(--border); color: var(--secondary); transition: var(--transition); white-space: nowrap; }
        .chip.active { background: var(--accent); border-color: var(--accent); color: #fff; }
        .load-more { margin: 6px 16px 20px; }
        .empty-state { text-align: center; padding: 48px 24px; color: var(--secondary); }
        .empty-state i { font-size: 2.2rem; color: #cbd5e1; margin-bottom: 12px; }

        /* skeleton */
        .skel { background: linear-gradient(90deg,#eef2f7 25%,#e2e8f0 37%,#eef2f7 63%); background-size: 400% 100%; animation: shimmer 1.3s infinite; border-radius: 16px; height: 210px; }
        @keyframes shimmer { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }

        /* ─────────── ABOUT ─────────── */
        .about-img { border-radius: 18px; overflow: hidden; margin-bottom: 18px; box-shadow: 0 8px 24px rgba(15,23,42,0.1); }
        .about-img img { width: 100%; height: 210px; object-fit: cover; }
        .about-text h3 { font-size: 1.25rem; color: var(--primary); margin-bottom: 10px; }
        .about-text p { color: var(--secondary); font-size: 0.88rem; margin-bottom: 14px; }
        .feat-list { display: grid; gap: 9px; margin: 14px 0; }
        .feat-item { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 500; color: var(--primary); }
        .feat-item i { color: var(--accent); }
        .certs { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
        .cert { background: #e2e8f0; color: var(--primary); padding: 6px 12px; border-radius: 8px; font-size: 0.72rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        /* clients marquee */
        .marquee { overflow: hidden; white-space: nowrap; padding: 8px 0; position: relative; }
        .marquee-track { display: inline-flex; align-items: center; animation: marquee 26s linear infinite; }
        .m-logo { display: inline-flex; align-items: center; gap: 8px; margin: 0 16px; filter: grayscale(1); opacity: 0.6; }
        .m-logo img { height: 32px; width: auto; max-width: 110px; object-fit: contain; }
        .m-logo span { font-size: 0.82rem; font-weight: 700; color: #94a3b8; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        /* testimonial */
        .testi-card { width: 270px; background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 18px; box-shadow: 0 2px 10px rgba(15,23,42,0.04); white-space: normal; }
        .testi-card .quote { color: var(--accent); font-size: 1.3rem; margin-bottom: 6px; }
        .testi-card p { font-style: italic; color: var(--secondary); font-size: 0.85rem; margin-bottom: 14px; }
        .testi-author { display: flex; align-items: center; gap: 10px; }
        .testi-av { width: 38px; height: 38px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .testi-author h5 { font-size: 0.85rem; color: var(--primary); }
        .testi-author small { color: var(--accent); font-size: 0.72rem; font-weight: 600; }

        /* faq */
        .faq-item { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 10px; overflow: hidden; }
        .faq-q { padding: 15px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; font-size: 0.88rem; font-weight: 600; color: var(--primary); }
        .faq-q i { transition: transform 0.3s; color: var(--secondary); flex-shrink: 0; }
        .faq-a { max-height: 0; overflow: hidden; transition: max-height 0.35s ease, padding 0.35s ease; padding: 0 16px; color: var(--secondary); font-size: 0.84rem; }
        .faq-item.open .faq-a { max-height: 400px; padding: 0 16px 15px; }
        .faq-item.open .faq-q i { transform: rotate(180deg); color: var(--accent); }

        /* video */
        .video-card { border-radius: 16px; overflow: hidden; background: #fff; border: 1px solid var(--border); margin-bottom: 14px; }
        .video-frame { position: relative; padding-bottom: 56.25%; background: #000 center/cover no-repeat; cursor: pointer; }
        .video-frame::before { content: ''; position: absolute; inset: 0; background: rgba(15,23,42,0.28); }
        .video-play { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 54px; height: 54px; border-radius: 50%; background: rgba(234,88,12,0.95); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; border: none; z-index: 2; box-shadow: 0 6px 18px rgba(0,0,0,0.4); }
        .video-play i { margin-left: 2px; }
        .video-play:active { transform: translate(-50%,-50%) scale(0.92); }
        .video-card .vi { padding: 13px 15px; }
        .video-card .vi h4 { font-size: 0.9rem; color: var(--primary); margin-bottom: 3px; }
        .video-card .vi p { font-size: 0.8rem; color: var(--secondary); }
        /* video fullscreen overlay (mobile: langsung penuh layar) */
        .video-fs { position: fixed; inset: 0; z-index: 500; background: #000; display: none; align-items: center; justify-content: center; }
        .video-fs.open { display: flex; }
        .video-fs-inner { position: relative; width: 100%; padding-bottom: 56.25%; }
        .video-fs-inner iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
        .video-fs-close { position: absolute; top: calc(12px + var(--safe-top)); right: 14px; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.16); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; z-index: 3; backdrop-filter: blur(4px); }
        /* isi artikel dalam bottom-sheet */
        .article-body { white-space: pre-line; color: var(--secondary); font-size: 0.9rem; line-height: 1.75; margin-bottom: 4px; }

        /* blog */
        .blog-card { display: flex; gap: 12px; background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 10px; margin-bottom: 12px; cursor: pointer; }
        .blog-card:active { transform: scale(0.99); }
        .blog-thumb { width: 90px; height: 90px; border-radius: 10px; background-size: cover; background-position: center; flex-shrink: 0; }
        .blog-info { display: flex; flex-direction: column; }
        .blog-info .bm { font-size: 0.68rem; font-weight: 600; color: var(--accent); }
        .blog-info h4 { font-size: 0.88rem; color: var(--primary); margin: 3px 0 5px; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .blog-info p { font-size: 0.76rem; color: var(--secondary); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ─────────── CTA ─────────── */
        .cta { margin: 20px 16px; background: linear-gradient(135deg, var(--primary), #1e293b); border-radius: 20px; padding: 26px 20px; text-align: center; color: #fff; }
        .cta h3 { font-size: 1.15rem; margin-bottom: 8px; }
        .cta p { font-size: 0.85rem; color: #cbd5e1; margin-bottom: 18px; }
        .cta .btn { margin-bottom: 10px; }

        /* ─────────── CONTACT ─────────── */
        .contact-card { display: flex; gap: 14px; background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 15px; margin-bottom: 12px; }
        .contact-ic { width: 44px; height: 44px; border-radius: 12px; background: rgba(234,88,12,0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; }
        .contact-card h5 { font-size: 0.85rem; color: var(--primary); margin-bottom: 3px; }
        .contact-card p { font-size: 0.82rem; color: var(--secondary); }
        .form-field { margin-bottom: 12px; }
        .form-field input, .form-field textarea {
            width: 100%; padding: 13px 15px; border: 1.5px solid var(--border); border-radius: 12px; font-size: 0.9rem; color: var(--text-main); background: #fff; transition: var(--transition); outline: none;
        }
        .form-field input:focus, .form-field textarea:focus { border-color: var(--accent); }
        .form-err { color: #dc2626; font-size: 0.74rem; margin-top: 4px; }
        .alert-ok { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 13px 15px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 16px; }

        /* ─────────── BOTTOM TAB BAR ─────────── */
        .tabbar {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
            height: calc(var(--tab-h) + var(--safe-bottom)); padding-bottom: var(--safe-bottom);
            background: rgba(255,255,255,0.96); backdrop-filter: blur(14px);
            border-top: 1px solid var(--border);
            display: flex; align-items: stretch;
        }
        .tab {
            flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 3px;
            color: var(--secondary); font-size: 0.66rem; font-weight: 600; transition: var(--transition); position: relative;
        }
        .tab i { font-size: 1.15rem; }
        .tab.active { color: var(--accent); }
        .tab.active::before { content: ''; position: absolute; top: 0; width: 26px; height: 3px; border-radius: 0 0 4px 4px; background: var(--accent); }

        /* ─────────── BOTTOM SHEET (product detail) ─────────── */
        .sheet-backdrop { position: fixed; inset: 0; z-index: 300; background: rgba(15,23,42,0.55); backdrop-filter: blur(3px); opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
        .sheet-backdrop.open { opacity: 1; visibility: visible; }
        .sheet {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 301;
            background: #fff; border-radius: 22px 22px 0 0; max-height: 92vh; overflow-y: auto;
            transform: translateY(100%); transition: transform 0.34s cubic-bezier(0.22,1,0.36,1);
            padding-bottom: calc(20px + var(--safe-bottom));
        }
        .sheet.open { transform: translateY(0); }
        .sheet-handle { width: 42px; height: 5px; border-radius: 3px; background: #cbd5e1; margin: 10px auto 4px; }
        .sheet-close { position: absolute; top: 14px; right: 14px; width: 34px; height: 34px; border-radius: 50%; background: rgba(15,23,42,0.06); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1rem; z-index: 5; }
        .sheet-img { width: 100%; height: 240px; object-fit: cover; background: #f1f5f9; }
        .sheet-content { padding: 18px 18px 0; }
        .sheet-badge { display: inline-block; background: var(--primary); color: #fff; font-size: 0.68rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .sheet-content h2 { font-size: 1.3rem; color: var(--primary); margin-bottom: 8px; line-height: 1.3; }
        .sheet-content > p { color: var(--secondary); font-size: 0.88rem; margin-bottom: 16px; }
        .spec-list { background: var(--light-bg); border-radius: 12px; padding: 4px 14px; margin-bottom: 18px; }
        .spec-list div { display: flex; justify-content: space-between; padding: 11px 0; border-bottom: 1px solid var(--border); font-size: 0.84rem; }
        .spec-list div:last-child { border-bottom: none; }
        .spec-list span { color: var(--secondary); }
        .spec-list b { color: var(--primary); text-align: right; }

        /* toast */
        .toast { position: fixed; bottom: calc(var(--tab-h) + 20px + var(--safe-bottom)); left: 50%; transform: translateX(-50%) translateY(20px); background: var(--primary); color: #fff; padding: 11px 20px; border-radius: 30px; font-size: 0.82rem; z-index: 400; opacity: 0; transition: all 0.3s; pointer-events: none; white-space: nowrap; }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        .footer-note { text-align: center; padding: 22px 16px; color: #94a3b8; font-size: 0.76rem; }
    </style>
</head>
<body>

    {{-- ══════════ HEADER ══════════ --}}
    <header class="app-header">
        <a class="app-logo" onclick="showScreen('home')">
            @if(!empty($s['company_logo']))
                <img src="{{ Storage::url($s['company_logo']) }}" alt="{{ $s['company_name'] ?? '' }}">
            @else
                {{ explode(' ', $s['company_name'] ?? 'Company')[0] }}
                <span>{{ implode(' ', array_slice(explode(' ', $s['company_name'] ?? ''), 1)) }}</span>
            @endif
        </a>
        <div class="header-actions">
            <button class="icon-btn" onclick="showScreen('products'); setTimeout(()=>document.getElementById('mSearch').focus(),350)" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
            <a class="icon-btn wa" href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+produk+Anda" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
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
                <div class="sec-title">{{ $s['categories_title'] ?? 'Kategori' }}</div>
                <div class="sec-sub">{{ $s['categories_desc'] ?? 'Jelajahi kategori produk kami' }}</div>
            </div>
            <div class="rail">
                @foreach($categories as $cat)
                    <div class="cat-card" onclick="goCategory('{{ $cat->id }}')">
                        <div class="bg" style="background-image: url('{{ $cat->img ? Storage::url($cat->img) : 'https://images.unsplash.com/photo-1513828583835-c5417eb91e1d?auto=format&fit=crop&w=400&q=80' }}');"></div>
                        <div class="ov"><h4>{{ $cat->title }}</h4><p>{{ $cat->desc }}</p></div>
                    </div>
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
            <div class="rail" id="featured-rail"></div>
            @endif

            {{-- CTA --}}
            @if($show('show_cta'))
            <div class="cta">
                <h3>{{ $s['cta_title'] ?? 'Butuh Spesifikasi Lengkap?' }}</h3>
                <p>{{ $s['cta_desc'] ?? 'Dapatkan katalog produk lengkap kami.' }}</p>
                @if(!empty($s['ecatalog_file']))
                    <a href="{{ Storage::url($s['ecatalog_file']) }}" target="_blank" class="btn btn-primary btn-block"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog</a>
                @endif
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+meminta+Katalog+Produk" target="_blank" class="btn btn-wa btn-block"><i class="fa-brands fa-whatsapp"></i> Minta via WhatsApp</a>
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
                            @if($client->logo)<img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}">@endif
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
                            <p>{{ Str::limit($blog->desc, 80) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

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
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+perusahaan+Anda" target="_blank" class="btn btn-wa btn-block"><i class="fa-brands fa-whatsapp"></i> Chat via WhatsApp</a>
            </div>

            <div class="footer-note">{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</div>
        </section>

        {{-- ═══════════════════ SCREEN: KONTAK ═══════════════════ --}}
        <section class="screen" id="screen-contact">
            <div class="pad">
                <div class="sec-title">{{ $s['contact_title'] ?? 'Hubungi Kami' }}</div>
                <div class="sec-sub">{{ $s['contact_desc'] ?? 'Kami siap membantu kebutuhan Anda' }}</div>

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

                <a class="btn btn-wa btn-block" style="margin: 8px 0 22px;" href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+berkonsultasi" target="_blank"><i class="fa-brands fa-whatsapp"></i> Chat WhatsApp Sekarang</a>

                <div class="sec-title" style="font-size:1rem;margin-bottom:14px;">Kirim Pesan</div>
                @if(session('contact_success'))
                    <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> {{ session('contact_success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
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
                        <textarea name="message" rows="4" placeholder="Detail kebutuhan Anda..." required>{{ old('message') }}</textarea>
                        @error('message')<p class="form-err">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Kirim Permintaan <i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
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
        const SEARCH_URL = "{{ route('products.search') }}";
        const FALLBACK_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';

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
            return `<div class="prod-card" onclick="openSheet(${p.id})">
                <div class="prod-img">
                    ${p.badge ? `<span class="prod-badge">${p.badge}</span>` : ''}
                    <img src="${p.img || FALLBACK_IMG}" alt="${escapeHtml(p.title)}" loading="lazy">
                </div>
                <div class="prod-body">
                    <span class="prod-meta">${escapeHtml(p.meta || '')}</span>
                    <h4 class="prod-title">${escapeHtml(p.title)}</h4>
                    <div class="prod-foot">
                        <div class="prod-spec">${escapeHtml(p.specLabel || '')}<b>${escapeHtml(p.specVal || '-')}</b></div>
                        <button class="prod-wa" onclick="event.stopPropagation(); waProduct('${encodeURIComponent(p.title)}')"><i class="fa-brands fa-whatsapp"></i></button>
                    </div>
                </div>
            </div>`;
        }

        /* ───── FEATURED RAIL (home) ───── */
        var featuredRail = document.getElementById('featured-rail');
        if (featuredRail) {
            featuredRail.innerHTML = FEATURED.map(cardHTML).join('') ||
                '<div style="padding:0 16px;color:#94a3b8;font-size:0.85rem;">Belum ada produk.</div>';
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
                const items = data.products || [];
                if (reset) _resultsCache = items.slice();
                else _resultsCache.push(...items);
                const html = items.map(cardHTML).join('');
                if (reset) {
                    resultsEl.innerHTML = html || emptyState();
                } else {
                    resultsEl.insertAdjacentHTML('beforeend', html);
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
        function loadMore() { curPage++; loadProducts(false); }
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
        function findProduct(id) {
            // featured first; else the currently-rendered / fetched results
            return FEATURED.find(p => p.id === id) || _resultsCache.find(p => p.id === id);
        }
        function openSheet(id) {
            const p = findProduct(id);
            if (!p) return;
            document.getElementById('sheetBody').innerHTML = `
                <img class="sheet-img" src="${p.img || FALLBACK_IMG}" alt="${escapeHtml(p.title)}">
                <div class="sheet-content">
                    <span class="sheet-badge">${escapeHtml(p.meta || 'Produk')}</span>
                    <h2>${escapeHtml(p.title)}</h2>
                    <p>${escapeHtml(p.desc || '')}</p>
                    <div class="spec-list">
                        <div><span>${escapeHtml(p.specLabel || 'Spesifikasi')}</span><b>${escapeHtml(p.specVal || '-')}</b></div>
                        <div><span>Kecepatan Maks (RPM)</span><b>${escapeHtml(String(p.rpm || '-'))}</b></div>
                        <div><span>Material Aplikasi</span><b>${escapeHtml(p.material || '-')}</b></div>
                    </div>
                    <button class="btn btn-wa btn-block" onclick="waProduct('${encodeURIComponent(p.title)}', 'memesan')"><i class="fa-brands fa-whatsapp"></i> Pesan via WhatsApp</button>
                </div>`;
            showSheet();
        }
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
            $blogsData = $blogs->map(fn($b) => ['id' => $b->id, 'meta' => $b->meta, 'title' => $b->title, 'desc' => $b->desc, 'img' => $b->img ? Storage::url($b->img) : '']);
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
                    <div class="article-body">${escapeHtml(b.desc || '')}</div>
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
    </script>
</body>
</html>
