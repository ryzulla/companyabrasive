<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami | {{ $s['company_name'] ?? 'Company Profile' }}</title>

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
        img { max-width: 100%; display: block; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        .btn-primary { background-color: var(--accent); color: #fff; padding: 12px 28px; border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); border: none; cursor: pointer; font-size: 0.95rem; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: translateY(-2px); }
        .btn-outline { background: transparent; color: var(--accent); border: 2px solid var(--accent); padding: 12px 28px; border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); cursor: pointer; font-size: 0.95rem; }
        .btn-outline:hover { background: var(--accent); color: #fff; }

        /* NAVBAR */
        header { background-color: rgba(255,255,255,0.97); position: sticky; top: 0; width: 100%; z-index: 1000; box-shadow: 0 2px 16px rgba(0,0,0,0.07); backdrop-filter: blur(12px); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .logo { font-size: 1.3rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
        .logo span { color: var(--accent); }
        .logo img { height: 36px; width: auto; object-fit: contain; }
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
        .page-header p { color: #94a3b8; font-size: 1rem; max-width: 620px; }
        .breadcrumb { display: flex; gap: 8px; align-items: center; font-size: 0.85rem; color: #64748b; margin-bottom: 15px; }
        .breadcrumb a { color: #94a3b8; transition: var(--transition); }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb i { font-size: 0.7rem; }

        /* ABOUT */
        .section-padding { padding: 70px 0; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-img { border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .about-img img { width: 100%; height: 100%; object-fit: cover; }
        .about-text h2 { font-size: 1.9rem; color: var(--primary); margin-bottom: 16px; }
        .about-text h2::after { content: ''; display: block; width: 56px; height: 4px; background: var(--accent); margin-top: 12px; border-radius: 2px; }
        .about-text p { color: var(--secondary); margin-bottom: 18px; }
        .features-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px; margin-bottom: 24px; }
        .feature-item { display: flex; align-items: center; gap: 8px; font-weight: 500; color: var(--primary); font-size: 0.92rem; }
        .feature-item i { color: var(--accent); flex-shrink: 0; }
        .certifications { display: flex; gap: 10px; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 18px; flex-wrap: wrap; }
        .cert-badge { background: #e2e8f0; color: var(--primary); padding: 6px 13px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        /* MILESTONE */
        .milestone-section { background-color: var(--primary); padding: 56px 0; color: #fff; }
        .milestone-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
        .milestone-card i { font-size: 2.2rem; color: var(--accent); margin-bottom: 12px; display: block; }
        .milestone-card h3 { font-size: 2.4rem; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; justify-content: center; gap: 2px; }
        .milestone-card p { color: #94a3b8; font-size: 0.95rem; }

        /* CTA */
        .cta-banner { background: linear-gradient(135deg, var(--primary) 0%, #1e293b 100%); padding: 56px 24px; text-align: center; color: #fff; margin: 60px 0; border-radius: 14px; box-shadow: 0 10px 30px rgba(15,23,42,0.2); }
        .cta-banner h2 { font-size: 2rem; margin-bottom: 14px; }
        .cta-banner p { font-size: 1rem; color: #cbd5e1; max-width: 580px; margin: 0 auto 28px; }
        .cta-buttons { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }

        /* FOOTER */
        footer { background-color: #020617; color: #64748b; padding: 28px 0; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; font-size: 0.88rem; }

        /* FLOAT WA */
        .float-wa { position: fixed; width: 56px; height: 56px; bottom: 28px; right: 28px; background-color: #25d366; color: #fff; border-radius: 50%; text-align: center; font-size: 26px; box-shadow: 0 4px 15px rgba(37,211,102,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; animation: pulse-wa 2s infinite; }
        .float-wa:hover { background-color: #128C7E; transform: scale(1.1); }
        @keyframes pulse-wa { 0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.7); } 70% { box-shadow: 0 0 0 15px rgba(37,211,102,0); } 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); } }

        @media (max-width: 992px) {
            .about-grid { grid-template-columns: 1fr; gap: 36px; }
            .about-img { order: -1; max-height: 320px; }
            .milestone-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
        }
        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .nav-menu { display: none; }
            .page-header { padding: 36px 0 28px; }
            .page-header h1 { font-size: 1.7rem; }
            .section-padding { padding: 48px 0; }
            .about-text h2 { font-size: 1.6rem; }
            .features-list { grid-template-columns: 1fr; }
            .milestone-card h3 { font-size: 2rem; }
            .cta-banner { padding: 40px 18px; margin: 44px 0; }
            .cta-banner h2 { font-size: 1.6rem; }
            .cta-buttons .btn-primary, .cta-buttons .btn-outline { width: 100%; justify-content: center; }
        }
        @media (max-width: 480px) {
            .container { padding: 0 16px; }
            .page-header h1 { font-size: 1.45rem; }
            .milestone-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .milestone-card h3 { font-size: 1.8rem; }
            .float-wa { width: 50px; height: 50px; font-size: 22px; bottom: 20px; right: 16px; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                @if(!empty($s['company_logo']))
                    <img src="{{ Storage::url($s['company_logo']) }}" alt="{{ $s['company_name'] ?? '' }}">
                @else
                    {{ explode(' ', $s['company_name'] ?? 'Company')[0] }}
                    <span>{{ implode(' ', array_slice(explode(' ', $s['company_name'] ?? ''), 1)) }}</span>
                @endif
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link active">Tentang</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link">Produk</a></li>
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
        <a href="{{ route('about') }}" style="color:var(--accent)"><i class="fa-solid fa-circle-info"></i> Tentang</a>
        <a href="{{ route('products.index') }}"><i class="fa-solid fa-box-open"></i> Produk</a>
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
                <span style="color: #fff;">Tentang Kami</span>
            </div>
            <h1>{{ $s['about_title'] ?? 'Tentang Kami' }}</h1>
            <p>{{ $s['company_tagline'] ?? 'Mengenal lebih dekat perjalanan, nilai, dan komitmen kami.' }}</p>
        </div>
    </section>

    {{-- ABOUT --}}
    <section class="section-padding">
        <div class="container">
            <div class="about-grid">
                <div class="about-img">
                    <img src="{{ !empty($s['about_img']) ? Storage::url($s['about_img']) : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80' }}" alt="Tentang {{ $s['company_name'] ?? 'Kami' }}">
                </div>
                <div class="about-text">
                    <h2>{{ $s['about_title'] ?? 'Tentang Perusahaan Kami' }}</h2>
                    <p>{!! $s['about_desc'] ?? '' !!}</p>
                    <div class="features-list">
                        @foreach([1,2,3,4] as $i)
                            @if(!empty($s['about_feature_'.$i]))
                                <div class="feature-item"><i class="fa-solid fa-circle-check"></i> {{ $s['about_feature_'.$i] }}</div>
                            @endif
                        @endforeach
                    </div>
                    @if(!empty($s['about_cert_1']) || !empty($s['about_cert_2']) || !empty($s['about_cert_3']))
                    <div class="certifications">
                        @foreach([1,2,3] as $i)
                            @if(!empty($s['about_cert_'.$i]))
                                <span class="cert-badge">
                                    <i class="fa-solid {{ $s['about_cert_'.$i.'_icon'] ?? 'fa-certificate' }}"></i>
                                    {{ $s['about_cert_'.$i] }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- MILESTONE --}}
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

    {{-- CTA --}}
    <section class="container">
        <div class="cta-banner">
            <h2>{{ $s['cta_title'] ?? 'Siap Bekerja Sama dengan Kami?' }}</h2>
            <p>{{ $s['cta_desc'] ?? 'Diskusikan kebutuhan Anda bersama tim kami dan temukan solusi terbaik.' }}</p>
            <div class="cta-buttons">
                <a href="{{ route('products.index') }}" class="btn-primary"><i class="fa-solid fa-box-open"></i> Lihat Produk Kami</a>
                <a href="{{ route('home') }}#contact" class="btn-outline" style="color:#fff;border-color:#fff;"><i class="fa-solid fa-paper-plane"></i> Hubungi Kami</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+perusahaan+Anda" class="float-wa" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <script>
        function toggleMobileNav() {
            document.getElementById('mobileNav').classList.toggle('open');
            document.getElementById('hamburger').classList.toggle('open');
        }

        // Milestone counter animation
        const counters = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const counter = entry.target;
                const target = +counter.getAttribute('data-target');
                const inc = Math.max(1, target / 100);
                const update = () => {
                    const count = +counter.innerText;
                    if (count < target) { counter.innerText = Math.ceil(count + inc); requestAnimationFrame(update); }
                    else counter.innerText = target;
                };
                update();
                observer.unobserve(counter);
            });
        }, { threshold: 0.5 });
        counters.forEach(c => counterObserver.observe(c));
    </script>
</body>
</html>
