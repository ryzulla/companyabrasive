<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --transition: all 0.3s ease;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { background-color: var(--light-bg); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        img { width: 100%; height: auto; display: block; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .section-padding { padding: 80px 0; }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-header h2 { font-size: 2.2rem; color: var(--primary); font-weight: 700; margin-bottom: 15px; }
        .section-header h2::after { content: ''; display: block; width: 60px; height: 4px; background: var(--accent); margin: 10px auto 0; border-radius: 2px; }
        .section-header p { color: var(--secondary); font-size: 1rem; max-width: 600px; margin: 0 auto; }

        .btn-primary { background-color: var(--accent); color: #fff; padding: 12px 28px; border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); border: none; cursor: pointer; font-size: 0.95rem; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: translateY(-2px); }
        .btn-outline { background: transparent; color: var(--accent); border: 2px solid var(--accent); padding: 12px 28px; border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); cursor: pointer; font-size: 0.95rem; }
        .btn-outline:hover { background: var(--accent); color: #fff; }

        /* ── NAVBAR ── */
        header { background-color: rgba(255,255,255,0.97); backdrop-filter: blur(12px); position: fixed; top: 0; width: 100%; z-index: 1000; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        .nav-container { display: flex; justify-content: space-between; align-items: center; height: 72px; }
        .logo { font-size: 1.3rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
        .logo span { color: var(--accent); }
        .nav-menu { display: flex; gap: 22px; align-items: center; }
        .nav-link { font-weight: 500; color: var(--secondary); transition: var(--transition); font-size: 0.92rem; }
        .nav-link:hover, .nav-link.active { color: var(--accent); }
        .hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; cursor: pointer; background: none; border: none; padding: 8px; border-radius: 6px; flex-shrink: 0; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--primary); border-radius: 2px; transition: all 0.28s ease; }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
        /* Mobile nav — animasi dengan visibility+opacity+transform, bukan display toggle */
        .mobile-nav { display: flex; flex-direction: column; position: fixed; top: 72px; left: 0; right: 0; background: #fff; z-index: 999; box-shadow: 0 8px 24px rgba(0,0,0,0.1); padding: 4px 0 8px; border-top: 1px solid #f1f5f9; visibility: hidden; opacity: 0; transform: translateY(-6px); transition: opacity 0.22s ease, transform 0.22s ease, visibility 0.22s; }
        .mobile-nav.open { visibility: visible; opacity: 1; transform: translateY(0); }
        .mobile-nav a { padding: 14px 24px; color: var(--primary); font-weight: 500; font-size: 0.95rem; border-bottom: 1px solid #f8fafc; display: flex; align-items: center; gap: 12px; transition: background 0.15s, color 0.15s; }
        .mobile-nav a:last-child { border-bottom: none; }
        .mobile-nav a:active, .mobile-nav a:hover { background: #f8fafc; color: var(--accent); }
        .mobile-nav a i { width: 18px; color: var(--accent); font-size: 0.88rem; flex-shrink: 0; }
        /* Backdrop overlay */
        .nav-backdrop { display: none; position: fixed; inset: 0; top: 72px; background: rgba(15,23,42,0.25); z-index: 998; }
        .nav-backdrop.open { display: block; }

        /* ── HERO ── */
        .hero { min-height: 100vh; background: linear-gradient(rgba(15,23,42,0.82), rgba(15,23,42,0.82)), url('https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1920&q=80') no-repeat center center/cover; display: flex; align-items: center; color: #fff; padding-top: 72px; background-size: cover; background-position: center; }
        .hero-content { max-width: 700px; }
        .hero h1 { font-size: 3.2rem; font-weight: 700; line-height: 1.2; margin-bottom: 18px; }
        .hero h1 span { color: var(--accent); }
        .hero p { font-size: 1.1rem; color: #cbd5e1; margin-bottom: 32px; }
        .hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; }

        /* ── ABOUT ── */
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-img { border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .about-text h3 { font-size: 1.7rem; color: var(--primary); margin-bottom: 16px; }
        .about-text p { color: var(--secondary); margin-bottom: 18px; }
        .features-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px; margin-bottom: 24px; }
        .feature-item { display: flex; align-items: center; gap: 8px; font-weight: 500; color: var(--primary); font-size: 0.92rem; }
        .feature-item i { color: var(--accent); flex-shrink: 0; }
        .certifications { display: flex; gap: 10px; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 18px; flex-wrap: wrap; }
        .cert-badge { background: #e2e8f0; color: var(--primary); padding: 5px 12px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        /* ── MILESTONE ── */
        .milestone-section { background-color: var(--primary); padding: 60px 0; color: #fff; }
        .milestone-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
        .milestone-card i { font-size: 2.2rem; color: var(--accent); margin-bottom: 12px; display: block; }
        .milestone-card h3 { font-size: 2.4rem; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; justify-content: center; gap: 2px; }
        .milestone-card p { color: #94a3b8; font-size: 0.95rem; }

        /* ── GRID UTILS ── */
        .dynamic-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; }

        /* ── CATEGORIES ── */
        .categories { background-color: #fff; }
        .category-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 210px)); justify-content: center; gap: 20px; }
        .category-card { position: relative; height: 200px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.07); cursor: pointer; transition: var(--transition); }
        .category-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.12); }
        .category-img { height: 100%; background-size: cover; background-position: center; transition: transform 0.4s ease; }
        .category-card:hover .category-img { transform: scale(1.08); }
        .category-overlay { position: absolute; inset: 0; background: linear-gradient(transparent 30%, rgba(15,23,42,0.88)); display: flex; flex-direction: column; justify-content: flex-end; padding: 18px; color: #fff; }
        .category-overlay h3 { font-size: 1rem; margin-bottom: 4px; font-weight: 600; }
        .category-overlay p { font-size: 0.75rem; color: #cbd5e1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ── FILTER / PRODUCT ── */
        .filter-container { display: flex; justify-content: center; gap: 8px; margin-bottom: 36px; flex-wrap: wrap; }
        .filter-btn { padding: 6px 16px; border: 2px solid var(--accent); background: transparent; color: var(--accent); border-radius: 30px; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: var(--transition); white-space: nowrap; }
        .filter-btn.active, .filter-btn:hover { background: var(--accent); color: #fff; }
        .product-card { background-color: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.05); transition: var(--transition); border: 1px solid #f1f5f9; cursor: pointer; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 28px rgba(0,0,0,0.1); }
        .product-img-wrapper { position: relative; height: 200px; background-color: #f1f5f9; overflow: hidden; }
        .product-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .product-card:hover .product-img-wrapper img { transform: scale(1.05); }
        .product-badge { position: absolute; top: 12px; left: 12px; background-color: var(--primary); color: #fff; padding: 3px 10px; font-size: 0.72rem; font-weight: 600; border-radius: 20px; text-transform: uppercase; }
        .product-info { padding: 18px; flex-grow: 1; display: flex; flex-direction: column; }
        .product-meta { font-size: 0.72rem; color: var(--accent); font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
        .product-title { font-size: 1rem; color: var(--primary); font-weight: 600; margin-bottom: 8px; line-height: 1.4; }
        .product-desc { font-size: 0.83rem; color: var(--secondary); margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1; }
        .product-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 12px; }
        .product-spec { font-size: 0.73rem; color: var(--secondary); }
        .product-spec span { display: block; font-weight: 600; color: var(--primary); font-size: 0.8rem; }
        .btn-sm { background-color: #e2e8f0; color: var(--primary); width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: var(--transition); font-size: 0.85rem; border: none; cursor: pointer; flex-shrink: 0; }
        .product-card:hover .btn-sm { background-color: var(--accent); color: #fff; }

        /* ── VIDEO ── */
        .video-card { background-color: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; transition: var(--transition); }
        .video-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
        .video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; background: #000; }
        .video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        .video-info { padding: 18px; }
        .video-info h3 { font-size: 1rem; color: var(--primary); margin-bottom: 6px; font-weight: 600; }
        .video-info p { font-size: 0.87rem; color: var(--secondary); }

        /* ── CTA ── */
        .cta-banner { background: linear-gradient(135deg, var(--primary) 0%, #1e293b 100%); padding: 56px 24px; text-align: center; color: #fff; margin: 60px 0; border-radius: 14px; box-shadow: 0 10px 30px rgba(15,23,42,0.2); }
        .cta-banner h2 { font-size: 2rem; margin-bottom: 14px; }
        .cta-banner p { font-size: 1rem; color: #cbd5e1; max-width: 580px; margin: 0 auto 28px; }
        .cta-buttons { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }

        /* ── CLIENTS MARQUEE ── */
        .clients-section { background: #fff; overflow: hidden; }
        .marquee-container { width: 100%; overflow: hidden; white-space: nowrap; position: relative; padding: 16px 0; }
        .marquee-container::before, .marquee-container::after { content: ''; position: absolute; top: 0; width: 80px; height: 100%; z-index: 2; }
        .marquee-container::before { left: 0; background: linear-gradient(to right, #fff, transparent); }
        .marquee-container::after { right: 0; background: linear-gradient(to left, #fff, transparent); }
        .marquee-track { display: inline-block; animation: marquee 30s linear infinite; }
        .client-logo { display: inline-flex; align-items: center; gap: 10px; margin: 0 20px; transition: var(--transition); filter: grayscale(100%); opacity: 0.55; white-space: nowrap; vertical-align: middle; }
        .client-logo img { height: 36px; width: auto; max-width: 130px; object-fit: contain; }
        .client-logo span { font-size: 0.9rem; font-weight: 700; color: #94a3b8; }
        .client-logo:hover { filter: grayscale(0%); opacity: 1; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        /* ── TESTIMONIALS ── */
        .testimonial-card { background: var(--card-bg); padding: 28px; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        .testi-text { font-style: italic; color: var(--secondary); margin-bottom: 18px; font-size: 0.92rem; }
        .testi-author { display: flex; align-items: center; gap: 12px; }
        .testi-avatar { width: 44px; height: 44px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary); flex-shrink: 0; font-size: 1.1rem; }
        .testi-info h4 { color: var(--primary); font-size: 0.95rem; }
        .testi-info p { color: var(--accent); font-size: 0.78rem; font-weight: 600; }

        /* ── FAQ ── */
        .faq-section { background: #fff; }
        .faq-container { max-width: 800px; margin: 0 auto; }
        .faq-item { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
        .faq-question { padding: 18px 20px; background: var(--card-bg); display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-weight: 600; color: var(--primary); transition: var(--transition); gap: 12px; font-size: 0.93rem; }
        .faq-question:hover { color: var(--accent); }
        .faq-question i { transition: transform 0.3s ease; flex-shrink: 0; }
        .faq-answer { padding: 0 20px; max-height: 0; background: var(--light-bg); color: var(--secondary); transition: max-height 0.35s ease, padding 0.35s ease; overflow: hidden; font-size: 0.9rem; }
        .faq-item.active .faq-answer { padding: 18px 20px; max-height: 500px; }
        .faq-item.active .faq-question i { transform: rotate(180deg); color: var(--accent); }

        /* ── BLOG ── */
        .blog-card { background: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.05); transition: var(--transition); }
        .blog-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
        .blog-img { height: 190px; background-size: cover; background-position: center; }
        .blog-content { padding: 22px; }
        .blog-meta { font-size: 0.78rem; color: var(--accent); font-weight: 600; margin-bottom: 8px; }
        .blog-content h3 { font-size: 1.05rem; color: var(--primary); margin-bottom: 8px; line-height: 1.4; }
        .blog-content p { color: var(--secondary); font-size: 0.87rem; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .read-more { color: var(--primary); font-weight: 600; font-size: 0.87rem; display: inline-flex; align-items: center; gap: 5px; transition: var(--transition); }
        .read-more:hover { color: var(--accent); }

        /* ── MODAL ── */
        .modal { display: none; position: fixed; z-index: 2000; inset: 0; background: rgba(15,23,42,0.82); backdrop-filter: blur(6px); opacity: 0; transition: opacity 0.3s ease; padding: 16px; }
        .modal.show { display: flex; align-items: center; justify-content: center; opacity: 1; }
        .modal-content { background: var(--card-bg); border-radius: 14px; width: 100%; max-width: 820px; max-height: 92vh; overflow-y: auto; position: relative; transform: translateY(-20px); transition: transform 0.3s ease; }
        .modal.show .modal-content { transform: translateY(0); }
        .close-modal { position: absolute; right: 16px; top: 12px; font-size: 26px; font-weight: bold; color: var(--secondary); cursor: pointer; z-index: 10; transition: var(--transition); line-height: 1; }
        .close-modal:hover { color: var(--accent); }
        .modal-body-grid { display: grid; grid-template-columns: 1fr 1fr; }
        .modal-img-side { background: #f1f5f9; border-radius: 14px 0 0 14px; overflow: hidden; }
        .modal-img { width: 100%; height: 100%; min-height: 280px; object-fit: cover; display: block; }
        .modal-details { padding: 32px 28px; display: flex; flex-direction: column; }
        .modal-details h2 { color: var(--primary); font-size: 1.6rem; margin-bottom: 8px; line-height: 1.3; }
        .modal-details .badge { display: inline-block; background: var(--primary); color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; margin-bottom: 12px; align-self: flex-start; }
        .modal-details p { color: var(--secondary); margin-bottom: 18px; font-size: 0.92rem; flex-grow: 1; }
        .modal-specs { background: var(--light-bg); padding: 14px; border-radius: 8px; margin-bottom: 22px; }
        .modal-specs div { display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding: 8px 0; font-size: 0.87rem; }
        .modal-specs div:last-child { border: none; }
        .modal-specs strong { color: var(--primary); }
        .modal-wa-btn { display: flex; align-items: center; justify-content: center; gap: 10px; background: #25d366; color: #fff; padding: 13px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.95rem; font-weight: 600; font-family: inherit; width: 100%; transition: background 0.3s; }
        .modal-wa-btn:hover { background: #1da851; }

        /* ── CONTACT ── */
        .contact { background-color: var(--primary); color: #fff; }
        .contact .section-header h2 { color: #fff; }
        .contact .section-header p { color: #94a3b8; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 50px; }
        .contact-details h3 { font-size: 1.3rem; margin-bottom: 22px; color: var(--accent); }
        .contact-item { display: flex; gap: 16px; margin-bottom: 22px; }
        .contact-icon { width: 46px; height: 46px; background: rgba(234,88,12,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.1rem; flex-shrink: 0; }
        .contact-text h4 { font-size: 0.93rem; margin-bottom: 4px; }
        .contact-text p { color: #94a3b8; font-size: 0.88rem; }
        .contact-form { background: rgba(255,255,255,0.03); padding: 32px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.07); }
        .form-group { margin-bottom: 18px; }
        .form-group input, .form-group textarea { width: 100%; padding: 13px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.92rem; font-family: inherit; transition: var(--transition); }
        .form-group input::placeholder, .form-group textarea::placeholder { color: rgba(255,255,255,0.38); }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--accent); outline: none; background: rgba(255,255,255,0.08); }
        .error-msg { color: #fca5a5; font-size: 0.78rem; margin-top: 4px; }

        /* ── FOOTER & FLOAT WA ── */
        footer { background: #020617; color: #64748b; padding: 28px 0; text-align: center; font-size: 0.88rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .float-wa { position: fixed; width: 56px; height: 56px; bottom: 28px; right: 28px; background: #25d366; color: #fff; border-radius: 50%; font-size: 26px; box-shadow: 0 4px 15px rgba(37,211,102,0.45); z-index: 1000; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; animation: pulse-wa 2.5s infinite; }
        .float-wa:hover { background: #128C7E; transform: scale(1.1); }
        @keyframes pulse-wa { 0%,100% { box-shadow: 0 0 0 0 rgba(37,211,102,0.6); } 60% { box-shadow: 0 0 0 14px rgba(37,211,102,0); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .milestone-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
            .about-grid { gap: 36px; }
        }
        @media (max-width: 992px) {
            .about-grid, .contact-grid { grid-template-columns: 1fr; gap: 36px; }
            .hero h1 { font-size: 2.6rem; }
            .modal-body-grid { grid-template-columns: 1fr; }
            .modal-img-side { border-radius: 14px 14px 0 0; }
            .modal-img { min-height: 220px; max-height: 260px; }
            .modal-details { padding: 24px 22px; }
            .cta-buttons { flex-direction: column; align-items: center; }
        }
        @media (max-width: 1024px) {
            .hamburger { display: flex; }
            .nav-menu { display: none; }
        }
        @media (max-width: 768px) {
            .section-padding { padding: 56px 0; }
            .section-header { margin-bottom: 36px; }
            .section-header h2 { font-size: 1.8rem; }
            .hero { min-height: calc(100svh - 0px); text-align: center; padding: 72px 0 40px; }
            .hero-content { max-width: 100%; }
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .hero-buttons { justify-content: center; }
            .milestone-grid { grid-template-columns: repeat(2, 1fr); }
            .milestone-card h3 { font-size: 2rem; }
            .about-grid { grid-template-columns: 1fr; }
            .about-img { order: -1; max-height: 280px; overflow: hidden; }
            .about-img img { height: 280px; object-fit: cover; width: 100%; }
            .features-list { grid-template-columns: 1fr; }
            .dynamic-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
            .category-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 170px)); gap: 14px; }
            .category-card { height: 170px; }
            .contact-form { padding: 22px; }
            .cta-banner { padding: 40px 18px; margin: 40px 0; }
            .cta-banner h2 { font-size: 1.6rem; }
            .cta-buttons .btn-primary, .cta-buttons .btn-outline { width: 100%; justify-content: center; }
            .float-wa { width: 50px; height: 50px; font-size: 22px; bottom: 20px; right: 16px; }
        }
        @media (max-width: 480px) {
            .container { padding: 0 16px; }
            .section-padding { padding: 44px 0; }
            .section-header h2 { font-size: 1.6rem; }
            .hero h1 { font-size: 1.7rem; }
            .btn-primary, .btn-outline { padding: 11px 20px; font-size: 0.88rem; }
            .milestone-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .milestone-card h3 { font-size: 1.8rem; }
            .milestone-card p { font-size: 0.85rem; }
            .category-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .category-card { height: 150px; }
            .dynamic-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .product-img-wrapper { height: 140px; }
            .product-info { padding: 10px 12px 12px; }
            .product-title { font-size: 0.88rem; }
            .product-desc { font-size: 0.77rem; -webkit-line-clamp: 2; margin-bottom: 10px; }
            .btn-sm { font-size: 0.75rem; padding: 6px 12px; }
            .modal-details { padding: 18px 16px; }
            .modal-details h2 { font-size: 1.3rem; }
            .contact-grid { gap: 28px; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <header>
        <div class="container nav-container">
            <a href="#home" class="logo">
                @if(!empty($s['company_logo']))
                    <img src="{{ Storage::url($s['company_logo']) }}" alt="{{ $s['company_name'] ?? '' }}" style="height:36px;width:auto;object-fit:contain;">
                @else
                    {{ explode(' ', $s['company_name'] ?? 'Company')[0] }}
                    <span>{{ implode(' ', array_slice(explode(' ', $s['company_name'] ?? ''), 1)) }}</span>
                @endif
            </a>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Beranda</a></li>
                <li><a href="#about" class="nav-link">Tentang</a></li>
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
        <a href="#about"    onclick="closeMobileNav()"><i class="fa-solid fa-circle-info"></i> Tentang</a>
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
                <p>{{ $s['hero_subtitle'] ?? '' }}</p>
                <div class="hero-buttons">
                    <a href="#products" class="btn-primary">{{ $s['hero_btn_primary'] ?? 'Jelajahi Produk' }} <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="#contact" class="btn-outline" style="color:#fff;border-color:#fff;">{{ $s['hero_btn_secondary'] ?? 'Minta Penawaran' }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- TENTANG KAMI --}}
    <section id="about" class="container section-padding">
        <div class="about-grid">
            <div class="about-img">
                @if(!empty($s['about_img']))
                    <img src="{{ Storage::url($s['about_img']) }}" alt="Tentang Kami">
                @else
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80" alt="Tentang Kami">
                @endif
            </div>
            <div class="about-text">
                <h3>{{ $s['about_title'] ?? '' }}</h3>
                <p>{!! $s['about_desc'] ?? '' !!}</p>
                <div class="features-list">
                    @foreach([1,2,3,4] as $i)
                        @if(!empty($s['about_feature_'.$i]))
                            <div class="feature-item"><i class="fa-solid fa-circle-check"></i> {{ $s['about_feature_'.$i] }}</div>
                        @endif
                    @endforeach
                </div>
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

    {{-- KATEGORI --}}
    @if($categories->count())
    <section id="categories" class="categories section-padding">
        <div class="container">
            <div class="section-header">
                <h2>{{ $s['categories_title'] ?? 'Kategori Produk' }}</h2>
                <p>{{ $s['categories_desc'] ?? '' }}</p>
            </div>
            <div class="category-grid">
                @foreach($categories as $cat)
                    <div class="category-card" onclick="triggerFilter('{{ $cat->id }}')">
                        <div class="category-img" style="background-image: url('{{ $cat->img ? Storage::url($cat->img) : 'https://images.unsplash.com/photo-1513828583835-c5417eb91e1d?auto=format&fit=crop&w=400&q=80' }}');"></div>
                        <div class="category-overlay">
                            <h3>{{ $cat->title }}</h3>
                            <p>{{ $cat->desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- PRODUK --}}
    <section id="products" class="container section-padding" style="padding-bottom: 40px;">
        <div class="section-header">
            <h2>{{ $s['products_title'] ?? 'Katalog Produk' }}</h2>
            <p>{{ $s['products_desc'] ?? '' }}</p>
        </div>
        <div class="filter-container" id="filter-container"></div>
        <div id="product-container" class="dynamic-grid"></div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('products.index') }}" class="btn-primary" style="display: inline-flex;">
                Lihat Semua Produk <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    {{-- MODAL PRODUK --}}
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    {{-- VIDEO --}}
    @if($videos->count())
    <section class="container section-padding" style="padding-top: 0;">
        <div class="section-header" style="margin-bottom: 30px;">
            <h2 style="font-size: 2rem;">{{ $s['video_title'] ?? 'Video' }}</h2>
            <p>{{ $s['video_desc'] ?? '' }}</p>
        </div>
        <div class="dynamic-grid">
            @foreach($videos as $video)
                <div class="video-card">
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/{{ $video->id }}" title="{{ $video->title }}" allowfullscreen></iframe>
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
    <section class="container">
        <div class="cta-banner">
            <h2>{{ $s['cta_title'] ?? 'Butuh Spesifikasi Teknis Lengkap?' }}</h2>
            <p>{{ $s['cta_desc'] ?? '' }}</p>
            <div class="cta-buttons">
                @if(!empty($s['ecatalog_file']))
                    <a href="{{ Storage::url($s['ecatalog_file']) }}" target="_blank" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog (PDF)</a>
                @else
                    <a href="#contact" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> Download E-Catalog (PDF)</a>
                @endif
                <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+meminta+Katalog+Produk" target="_blank" class="btn-outline" style="color: #fff; border-color: #fff;"><i class="fa-brands fa-whatsapp"></i> Minta via WhatsApp</a>
            </div>
        </div>
    </section>

    {{-- KLIEN --}}
    @if($clients->count())
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
                            <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}">
                        @endif
                        <span>{{ $client->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- TESTIMONI --}}
    @if($testimonials->count())
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
    @if($faqs->count())
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
    @if($blogs->count())
    <section class="container section-padding">
        <div class="section-header">
            <h2>{{ $s['blog_title'] ?? 'Blog' }}</h2>
            <p>{{ $s['blog_desc'] ?? '' }}</p>
        </div>
        <div class="dynamic-grid">
            @foreach($blogs as $blog)
                <div class="blog-card">
                    <div class="blog-img" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=400&q=80' }}')"></div>
                    <div class="blog-content">
                        <div class="blog-meta">{{ $blog->meta }}</div>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ Str::limit($blog->desc, 100) }}</p>
                        <a href="#" class="read-more">Baca Artikel <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- KONTAK --}}
    <section id="contact" class="contact section-padding">
        <div class="container">
            <div class="section-header">
                <h2>{{ $s['contact_title'] ?? 'Hubungi Kami' }}</h2>
                <p>{{ $s['contact_desc'] ?? '' }}</p>
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
                </div>
                <div class="contact-form">
                    @if(session('contact_success'))
                        <div style="background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 14px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                            {{ session('contact_success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
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
                            <textarea name="message" rows="4" placeholder="Detail kebutuhan Anda..." required>{{ old('message') }}</textarea>
                            @error('message')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn-primary">Kirim Permintaan <i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>{{ $s['footer_text'] ?? '© '.date('Y').' All Rights Reserved.' }}</p>
        </div>
    </footer>

    <a href="https://wa.me/{{ $s['wa_number'] ?? '' }}?text=Halo%2C+saya+ingin+bertanya+tentang+produk+Anda" class="float-wa" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

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

        const productsData = @json($products);

        const waNumber = "{{ $s['wa_number'] ?? '' }}";

        let currentFilter = 'all';

        function renderFilterButtons() {
            const container = document.getElementById('filter-container');
            let html = `<button class="filter-btn active" data-filter="all">Semua Produk</button>`;
            categoriesData.forEach(cat => {
                html += `<button class="filter-btn" data-filter="${cat.id}">${cat.title}</button>`;
            });
            container.innerHTML = html;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    currentFilter = e.target.getAttribute('data-filter');
                    renderProducts(currentFilter);
                });
            });
        }

        function triggerFilter(type) {
            document.querySelector('#products').scrollIntoView();
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active');
                if(b.getAttribute('data-filter') === type) b.classList.add('active');
            });
            currentFilter = type;
            renderProducts(type);
        }

        function renderProducts(filterType) {
            const container = document.getElementById('product-container');
            const filteredData = filterType === 'all' ? productsData : productsData.filter(p => p.type === filterType);

            if(filteredData.length === 0) {
                container.innerHTML = `<p style="grid-column: 1/-1; text-align:center; color:#666;">Belum ada produk di kategori ini.</p>`;
                return;
            }

            container.innerHTML = filteredData.map(prod => `
                <div class="product-card" onclick="openModal(${prod.id})">
                    <div class="product-img-wrapper">
                        ${prod.badge ? `<span class="product-badge">${prod.badge}</span>` : ''}
                        <img src="${prod.img || 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80'}" alt="${prod.title}">
                    </div>
                    <div class="product-info">
                        <div class="product-meta">${prod.meta}</div>
                        <h3 class="product-title">${prod.title}</h3>
                        <p class="product-desc">${prod.desc}</p>
                        <div class="product-footer">
                            <div class="product-spec">${prod.specLabel}:<span>${prod.specVal}</span></div>
                            <button class="btn-sm" onclick="event.stopPropagation(); window.open('https://wa.me/${waNumber}?text=Halo%2C+saya+tertarik+dengan+${encodeURIComponent(prod.title)}', '_blank')"><i class="fa-brands fa-whatsapp"></i></button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        const modal = document.getElementById('productModal');
        window.openModal = function(id) {
            const prod = productsData.find(p => p.id === id);
            if(!prod) return;
            document.getElementById('modal-body').innerHTML = `
                <div class="modal-body-grid">
                    <div class="modal-img-side">
                        <img src="${prod.img || 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=600&q=80'}" alt="${prod.title}" class="modal-img">
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
                        <button onclick="window.open('https://wa.me/${waNumber}?text=Halo%2C+saya+ingin+memesan+${encodeURIComponent(prod.title)}', '_blank')" class="modal-wa-btn"><i class="fa-brands fa-whatsapp"></i> Pesan via WhatsApp</button>
                    </div>
                </div>
            `;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        document.querySelector('.close-modal').onclick = () => { modal.classList.remove('show'); document.body.style.overflow = ''; }
        window.onclick = (e) => { if(e.target == modal) { modal.classList.remove('show'); document.body.style.overflow = ''; } }
        document.addEventListener('keydown', e => { if(e.key === 'Escape') { modal.classList.remove('show'); document.body.style.overflow = ''; } });

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
            renderProducts('all');
        });
    </script>
</body>
</html>
