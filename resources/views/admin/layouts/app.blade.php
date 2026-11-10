<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $adminSettings = \App\Models\Setting::allKeyed();
        $adminBrand = $adminSettings['company_name'] ?? 'Admin Panel';
        $superOnly = ['admin.categories.index', 'admin.clients.index', 'admin.users.index', 'admin.settings.index'];
        $isSuper = Auth::user()->isSuperuser();
        $navGroups = [
            ['label' => null, 'items' => [
                ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ]],
            ['label' => 'Konten', 'items' => [
                ['Kategori', 'admin.categories.index', 'admin.categories.*', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['Produk', 'admin.products.index', 'admin.products.*', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Blog', 'admin.blogs.index', 'admin.blogs.*', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['Video', 'admin.videos.index', 'admin.videos.*', 'M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ]],
            ['label' => 'Komunitas', 'items' => [
                ['Testimoni', 'admin.testimonials.index', 'admin.testimonials.*', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ['FAQ', 'admin.faqs.index', 'admin.faqs.*', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Klien / Mitra', 'admin.clients.index', 'admin.clients.*', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ]],
            ['label' => 'Lainnya', 'items' => [
                ['Pesan Masuk', 'admin.contacts.index', 'admin.contacts.*', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['Pengguna', 'admin.users.index', 'admin.users.*', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['Pengaturan', 'admin.settings.index', 'admin.settings.*', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ]],
        ];
    @endphp
    <title>@yield('title', 'Dashboard') · {{ $adminBrand }}</title>
    @if(!empty($adminSettings['favicon']))<link rel="icon" href="{{ Storage::url($adminSettings['favicon']) }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --a-bg: #f1f5f9;
            --a-surface: #ffffff;
            --a-border: #e2e8f0;
            --a-text: #0f172a;
            --a-muted: #64748b;
            --a-primary: #4f46e5;
            --a-primary-dark: #4338ca;
            --a-side: #0f172a;
            --a-side-hover: rgba(255,255,255,0.06);
        }
        html, body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        body { background: var(--a-bg); color: var(--a-text); margin: 0; }
        .admin-wrap { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .admin-sidebar { width: 252px; min-width: 252px; background: var(--a-side); color: #cbd5e1; display: flex; flex-direction: column; transition: transform 0.25s ease; z-index: 200; position: sticky; top: 0; height: 100vh; }
        .brand { height: 64px; display: flex; align-items: center; gap: 12px; padding: 0 20px; border-bottom: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }
        .brand-mark { width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; flex-shrink: 0; }
        .brand-name { font-size: 15px; font-weight: 600; color: #fff; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .brand-sub { font-size: 11px; color: #94a3b8; }
        .side-nav { flex: 1; overflow-y: auto; padding: 14px 12px; }
        .nav-link { display: flex; align-items: center; gap: 11px; padding: 9px 12px; border-radius: 8px; font-size: 13.5px; font-weight: 500; color: #cbd5e1; text-decoration: none; margin-bottom: 2px; position: relative; transition: background 0.15s, color 0.15s; }
        .nav-link:hover { background: var(--a-side-hover); color: #fff; }
        .nav-link.active { background: rgba(99,102,241,0.18); color: #fff; }
        .nav-link.active::before { content: ''; position: absolute; left: -12px; top: 8px; bottom: 8px; width: 3px; border-radius: 0 3px 3px 0; background: #818cf8; }
        .nav-link svg { width: 17px; height: 17px; flex-shrink: 0; opacity: 0.85; }
        .nav-link.active svg { color: #a5b4fc; opacity: 1; }
        .nav-label { font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.09em; color: #64748b; padding: 16px 12px 6px; }
        .nav-badge { margin-left: auto; background: #ef4444; color: #fff; font-size: 10.5px; font-weight: 600; padding: 1px 7px; border-radius: 20px; }
        .side-avatar { width: 34px; height: 34px; border-radius: 50%; background: #4f46e5; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; }

        /* ── MAIN ── */
        .admin-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar { height: 64px; background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--a-border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; position: sticky; top: 0; z-index: 100; }
        .topbar h2 { font-size: 17px; font-weight: 600; color: var(--a-text); margin: 0; }
        .topbar-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; color: var(--a-muted); text-decoration: none; padding: 7px 12px; border: 1px solid var(--a-border); border-radius: 8px; background: #fff; transition: all 0.15s; }
        .topbar-link:hover { color: var(--a-primary); border-color: #c7d2fe; background: #eef2ff; }
        .topbar-link svg { width: 15px; height: 15px; }
        .admin-content { flex: 1; padding: 28px; width: 100%; min-width: 0; }
        /* form & kartu detail memenuhi lebar konten (sebelumnya dibatasi max-w-xl/2xl/3xl) */
        .admin-content .max-w-xl, .admin-content .max-w-2xl, .admin-content .max-w-3xl { max-width: none; }
        .flash { margin-bottom: 18px; padding: 12px 16px; font-size: 14px; border-radius: 10px; display: flex; align-items: flex-start; gap: 10px; }
        .flash-ok { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; }
        .flash-err { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        .sidebar-toggle { display: none; align-items: center; justify-content: center; width: 38px; height: 38px; background: none; border: none; cursor: pointer; border-radius: 8px; flex-shrink: 0; color: #334155; }
        .sidebar-toggle:hover { background: #f1f5f9; }
        .sidebar-toggle svg { width: 21px; height: 21px; }
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 199; }
        .sidebar-backdrop.open { display: block; }

        /* ── PENYELARASAN GLOBAL SEMUA HALAMAN ADMIN ── */
        .admin-content .bg-white.rounded-xl { border-color: var(--a-border); box-shadow: 0 1px 2px rgba(15,23,42,0.04); }
        .admin-content table thead { background: #f8fafc; }
        .admin-content table thead th { font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--a-muted); padding-top: 12px; padding-bottom: 12px; }
        .admin-content table tbody tr:hover { background: #f8fafc; }
        .admin-content table td { vertical-align: middle; }
        .admin-content .bg-blue-600 { background-color: var(--a-primary); }
        .admin-content .hover\:bg-blue-700:hover { background-color: var(--a-primary-dark); }
        .admin-content .text-blue-600 { color: var(--a-primary); }
        .admin-content .hover\:text-blue-800:hover { color: var(--a-primary-dark); }
        .admin-content .focus\:ring-blue-500:focus { --tw-ring-color: #6366f1; }
        .admin-content input[type="text"], .admin-content input[type="email"], .admin-content input[type="url"], .admin-content input[type="number"], .admin-content input[type="password"], .admin-content select, .admin-content textarea { border-color: #cbd5e1; }
        .admin-content input:focus, .admin-content select:focus, .admin-content textarea:focus { border-color: #6366f1; }
        .admin-content button[type="submit"], .admin-content a.rounded-lg { font-weight: 500; }


        /* ── DATATABLES ── */
        .dataTables_wrapper { font-size: 13px; color: var(--a-muted); }
        .dataTables_wrapper .dt-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; }
        .dataTables_wrapper .dt-bottom { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 12px 20px; border-top: 1px solid #f1f5f9; }
        .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { float: none; margin: 0; padding: 0; text-align: left; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #cbd5e1; border-radius: 8px; padding: 7px 12px; margin-left: 8px; font-size: 13px; outline: none; min-width: 220px; color: var(--a-text); background: #fff; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .dataTables_wrapper .dataTables_length select { border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 28px 6px 10px; margin: 0 6px; font-size: 13px; background-color: #fff; outline: none; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid transparent !important; border-radius: 8px !important; padding: 5px 11px !important; margin: 0 2px !important; font-size: 13px; color: var(--a-muted) !important; background: transparent !important; box-shadow: none !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #eef2ff !important; color: var(--a-primary) !important; border-color: #c7d2fe !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: var(--a-primary) !important; color: #fff !important; border-color: var(--a-primary) !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { opacity: 0.4; background: transparent !important; color: var(--a-muted) !important; border-color: transparent !important; cursor: default; }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 0 !important; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #e2e8f0 !important; }
        table.dataTable.no-footer { border-bottom: none; }
        table.dataTable tbody tr { background: transparent; }
        table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc { cursor: pointer; }
        table.dataTable thead .sorting_disabled { cursor: default; }
        @media (max-width: 768px) {
            .dataTables_wrapper .dt-top, .dataTables_wrapper .dt-bottom { padding: 10px 0; border: none; }
            .dataTables_wrapper .dataTables_filter input { min-width: 0; width: 100%; margin: 6px 0 0; }
            .dataTables_wrapper .dataTables_filter label { display: block; }
            table.dataTable tbody tr { margin-bottom: 12px; }
        }

        @media (min-width: 769px) {
            .admin-username { display: inline !important; }
        }
        @media (max-width: 768px) {
            .sidebar-toggle { display: flex; }
            .topbar { padding: 0 14px; }
            .topbar-link span { display: none; }
            .user-trigger-name, .user-caret { display: none; }
            .user-trigger { padding: 3px; }
            .admin-sidebar { position: fixed; top: 0; left: 0; height: 100%; transform: translateX(-100%); box-shadow: 4px 0 24px rgba(0,0,0,0.25); }
            .admin-sidebar.open { transform: translateX(0); }
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

        /* ── USER MENU (topbar kanan) ── */
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .user-menu { position: relative; }
        .user-trigger { display: inline-flex; align-items: center; gap: 8px; background: none; border: 1px solid var(--a-border); border-radius: 999px; padding: 4px 10px 4px 4px; cursor: pointer; color: var(--a-text); font-size: 13px; font-weight: 500; font-family: inherit; transition: background .15s, border-color .15s; }
        .user-trigger:hover, .user-menu.open .user-trigger { background: #f8fafc; border-color: #c7d2fe; }
        .user-trigger .side-avatar { width: 28px; height: 28px; font-size: 12px; }
        .user-caret { width: 14px; height: 14px; color: var(--a-muted); transition: transform .15s; }
        .user-menu.open .user-caret { transform: rotate(180deg); }
        .user-dropdown { display: none; position: absolute; right: 0; top: calc(100% + 8px); min-width: 240px; background: #fff; border: 1px solid var(--a-border); border-radius: 12px; box-shadow: 0 12px 32px rgba(15,23,42,.12); padding: 6px; z-index: 300; }
        .user-menu.open .user-dropdown { display: block; }
        .user-dropdown-head { padding: 10px 12px 12px; border-bottom: 1px solid #f1f5f9; margin-bottom: 6px; }
        .user-dropdown-name { font-size: 14px; font-weight: 600; color: var(--a-text); }
        .user-dropdown-mail { font-size: 12px; color: var(--a-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }
        .user-dropdown-role { display: inline-block; margin-top: 6px; font-size: 11px; font-weight: 600; color: var(--a-primary); background: #eef2ff; border-radius: 999px; padding: 2px 8px; }
        .user-dropdown-item { display: flex; align-items: center; gap: 10px; width: 100%; padding: 9px 12px; border: none; background: none; border-radius: 8px; font-size: 13px; font-weight: 500; color: #334155; text-decoration: none; cursor: pointer; font-family: inherit; text-align: left; }
        .user-dropdown-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .user-dropdown-item:hover, .user-dropdown-item.active { background: #f1f5f9; }
        .user-dropdown-item.danger { color: #dc2626; }
        .user-dropdown-item.danger:hover { background: #fef2f2; }
    </style>
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>
<div class="admin-wrap">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            @if(!empty($adminSettings['company_logo']))
                <div class="brand-mark" style="background:#fff; padding:3px;"><img src="{{ Storage::url($adminSettings['company_logo']) }}" alt="" style="width:100%; height:100%; object-fit:contain;"></div>
            @else
                <div class="brand-mark">{{ strtoupper(mb_substr($adminBrand, 0, 1)) }}</div>
            @endif
            <div style="min-width:0;">
                <div class="brand-name">{{ $adminBrand }}</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="side-nav">
            @foreach($navGroups as $group)
                @continue(collect($group['items'])->reject(fn($i) => !$isSuper && in_array($i[1], $superOnly))->isEmpty())
                @if($group['label'])<div class="nav-label">{{ $group['label'] }}</div>@endif
                @foreach(collect($group['items'])->reject(fn($i) => !$isSuper && in_array($i[1], $superOnly)) as [$label, $route, $pattern, $icon])
                    <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                        {{ $label }}
                    </a>
                @endforeach
            @endforeach
        </nav>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:10px;">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Menu">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2>@yield('title', 'Dashboard')</h2>
            </div>
            <div class="topbar-right">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="topbar-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Lihat Website</span>
                </a>
                <div class="user-menu" id="userMenu">
                    <button type="button" class="user-trigger" onclick="toggleUserMenu(event)" aria-haspopup="true" aria-expanded="false" aria-label="Menu akun">
                        <span class="side-avatar">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="user-trigger-name">{{ Auth::user()->name }}</span>
                        <svg class="user-caret" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="user-dropdown" role="menu">
                        <div class="user-dropdown-head">
                            <div class="user-dropdown-name">{{ Auth::user()->name }}</div>
                            <div class="user-dropdown-mail">{{ Auth::user()->email }}</div>
                            <div class="user-dropdown-role">{{ Auth::user()->roleLabel() }}</div>
                        </div>
                        <a href="{{ route('admin.account.edit') }}" class="user-dropdown-item {{ request()->routeIs('admin.account.*') ? 'active' : '' }}" role="menuitem">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Pengaturan akun
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-dropdown-item danger" role="menuitem">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
                <div class="flash flash-ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-err">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
    $('table.js-datatable').each(function () {
        const nonSortable = [];
        $(this).find('thead th').each(function (i) {
            if (/^(aksi|gambar|logo|foto|thumbnail)$/i.test($(this).text().trim())) nonSortable.push(i);
        });
        $(this).DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [],
            autoWidth: false,
            columnDefs: [{ orderable: false, targets: nonSortable }],
            dom: '<"dt-top"lf>rt<"dt-bottom"ip>',
            language: {
                search: 'Cari:',
                searchPlaceholder: 'Ketik untuk mencari...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(disaring dari _MAX_ data)',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: { first: '«', last: '»', next: '›', previous: '‹' }
            }
        });
    });
});
</script>
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
function toggleUserMenu(e) {
    e.stopPropagation();
    var m = document.getElementById('userMenu');
    var open = m.classList.toggle('open');
    m.querySelector('.user-trigger').setAttribute('aria-expanded', open);
}
document.addEventListener('click', function(e) {
    var m = document.getElementById('userMenu');
    if (m && !m.contains(e.target)) { m.classList.remove('open'); m.querySelector('.user-trigger').setAttribute('aria-expanded', 'false'); }
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { var m = document.getElementById('userMenu'); if (m) m.classList.remove('open'); }
});
// Tutup sidebar saat resize ke desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeSidebar();
});
</script>
</body>
</html>
