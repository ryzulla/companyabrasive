<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .admin-wrap { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 240px;
            min-width: 240px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            transition: transform 0.25s ease;
            z-index: 200;
        }
        .admin-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            text-decoration: none;
            margin-bottom: 2px;
        }
        .nav-link:hover { background: #f3f4f6; color: #111827; }
        .nav-link.active { background: #f3f4f6; color: #111827; }
        .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9ca3af;
            padding: 12px 12px 4px;
        }
        /* Hamburger button — hanya muncul di mobile */
        .sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            flex-shrink: 0;
            color: #374151;
        }
        .sidebar-toggle:hover { background: #f3f4f6; }
        .sidebar-toggle svg { width: 20px; height: 20px; }
        /* Overlay backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.35);
            z-index: 199;
        }
        .sidebar-backdrop.open { display: block; }
        @media (min-width: 769px) {
            .admin-username { display: inline !important; }
        }
        @media (max-width: 768px) {
            .sidebar-toggle { display: flex; }
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            }
            .admin-sidebar.open { transform: translateX(0); }

            /* Konten lebih rapat di layar kecil */
            .admin-content { padding: 16px !important; }

            /* ─── TABEL → KARTU (tampilan asli mobile, bukan sekadar scroll) ─── */
            .admin-table-wrap { overflow: visible !important; }
            table.admin-table { min-width: 0 !important; width: 100%; }
            table.admin-table thead { display: none; }
            table.admin-table tbody { display: block; }
            table.admin-table tr {
                display: block;
                background: #fff;
                border: 1px solid #e5e7eb !important;
                border-radius: 12px;
                padding: 12px 14px;
                margin-bottom: 12px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }
            table.admin-table tr:last-child { margin-bottom: 0; }
            table.admin-table td {
                display: block;
                text-align: left;
                padding: 5px 0 5px 40% !important;
                position: relative;
                border: none !important;
                min-height: 30px;
                max-width: 100%;
                word-break: break-word;
            }
            table.admin-table td[data-label]::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                top: 5px;
                width: 36%;
                text-align: left;
                font-size: 12px;
                font-weight: 600;
                color: #6b7280;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            /* Sel gambar/thumbnail: tampil penuh di atas kartu, tanpa label */
            table.admin-table td.cell-media {
                padding: 0 0 10px 0 !important;
                margin-bottom: 6px;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            /* Sel aksi: tombol lebih besar, dipisah garis, rata kanan */
            table.admin-table td.cell-actions {
                padding: 12px 0 2px 0 !important;
                margin-top: 8px;
                border-top: 1px solid #f1f5f9 !important;
                text-align: right;
            }
            table.admin-table td.cell-actions > div { justify-content: flex-end; gap: 20px; }
            table.admin-table td.cell-actions a,
            table.admin-table td.cell-actions button { font-size: 14px !important; padding: 4px 0; }
        }
    </style>
</head>
<body style="background:#f3f4f6; margin:0;">

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>
<div class="admin-wrap">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">

        {{-- Brand --}}
        <div style="height:64px; display:flex; align-items:center; padding:0 20px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
            <span style="font-size:18px; font-weight:700; color:#1f2937;">Admin Panel</span>
        </div>

        {{-- Nav --}}
        <nav style="flex:1; overflow-y:auto; padding:16px 12px;">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="nav-label">Konten</div>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Kategori
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produk
            </a>

            <a href="{{ route('admin.blogs.index') }}"
               class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Blog
            </a>

            <a href="{{ route('admin.videos.index') }}"
               class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Video
            </a>

            <div class="nav-label">Komunitas</div>

            <a href="{{ route('admin.testimonials.index') }}"
               class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Testimoni
            </a>

            <a href="{{ route('admin.faqs.index') }}"
               class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                FAQ
            </a>

            <a href="{{ route('admin.clients.index') }}"
               class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Klien / Mitra
            </a>

            <div class="nav-label">Lainnya</div>

            <a href="{{ route('admin.contacts.index') }}"
               class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Pesan Masuk
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan
            </a>

        </nav>

        {{-- Logout --}}
        <div style="border-top:1px solid #e5e7eb; padding:12px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <header style="height:64px; background:#fff; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; padding:0 16px 0 24px; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Menu">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h2 style="font-size:16px; font-weight:600; color:#1f2937; margin:0;">@yield('title', 'Dashboard')</h2>
            </div>
            <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
                <span style="font-size:14px; color:#6b7280; display:none;" class="admin-username">{{ Auth::user()->name }}</span>
                <div style="width:36px; height:36px; border-radius:50%; background:#4f46e5; display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="admin-content" style="flex:1; padding:24px; overflow-y:auto;">

            @if(session('success'))
                <div style="margin-bottom:16px; padding:12px 16px; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; font-size:14px; border-radius:6px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; font-size:14px; border-radius:6px;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>
    </div>

</div>

@stack('scripts')
<script>
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('sidebarBackdrop').classList.toggle('open');
    document.body.style.overflow = document.getElementById('adminSidebar').classList.contains('open') ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}
// Tutup sidebar saat resize ke desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeSidebar();
});
</script>
</body>
</html>
