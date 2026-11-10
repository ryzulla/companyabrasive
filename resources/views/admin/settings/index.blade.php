@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
@php
    $v = fn($key, $def='') => $settings[$key] ?? $def;
    // Toggle default = tampil (1). Hanya '0' yang berarti disembunyikan.
    $isOn = fn($key) => ($settings[$key] ?? '1') !== '0';
@endphp

<form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; font-size:14px; border-radius:6px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- TAB BUTTONS --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:20px; overflow:hidden; box-shadow:0 1px 2px rgba(15,23,42,.04);">
        <div style="display:flex; overflow-x:auto; border-bottom:1px solid #e5e7eb;">
            @php
                $tabs = [
                    ['id' => 'identitas', 'label' => 'Identitas'],
                    ['id' => 'seo',       'label' => 'SEO'],
                    ['id' => 'hero',      'label' => 'Hero'],
                    ['id' => 'about',     'label' => 'Tentang'],
                    ['id' => 'statistik', 'label' => 'Statistik'],
                    ['id' => 'seksi',     'label' => 'Judul Seksi'],
                    ['id' => 'cta',       'label' => 'CTA'],
                    ['id' => 'kontak',    'label' => 'Kontak'],
                    ['id' => 'sosial',    'label' => 'Lokasi & Sosial'],
                    ['id' => 'footer',    'label' => 'Footer'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <button type="button" id="tab-btn-{{ $tab['id'] }}"
                        onclick="showTab('{{ $tab['id'] }}')"
                        style="padding:12px 20px; font-size:14px; font-weight:500; white-space:nowrap; background:none; border:none; cursor:pointer; color:#6b7280; border-bottom:2px solid transparent; margin-bottom:-1px;">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- PANEL: IDENTITAS --}}
    <div id="panel-identitas" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Identitas Perusahaan</h3>
            <div class="set-grid cols-2">
                <div>
                    <label class="field-label">Nama Pendek Perusahaan</label>
                    <input type="text" name="company_name" value="{{ $v('company_name') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Nama Lengkap Perusahaan</label>
                    <input type="text" name="company_full_name" value="{{ $v('company_full_name') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Tagline</label>
                    <input type="text" name="company_tagline" value="{{ $v('company_tagline') }}" class="field-input">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="field-label">Logo Perusahaan</label>
                    @php $currentLogo = $v('company_logo'); @endphp
                    <div style="position:relative; display:inline-block; width:100%;">
                        <input type="file" name="company_logo_file" accept="image/*" id="logoInput" style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none;">
                        <label for="logoInput" id="logoLabel" style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;min-height:120px;border:2px dashed #e2e8f0;border-radius:8px;cursor:pointer;overflow:hidden;transition:border-color .2s;box-sizing:border-box;">
                            @if($currentLogo)
                                <div id="logoEmpty" style="display:none;"></div>
                                <img id="logoPreview" src="{{ Storage::url($currentLogo) }}" alt="Logo" style="max-height:100px;max-width:100%;object-fit:contain;padding:12px;">
                            @else
                                <div id="logoEmpty" style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#94a3b8;padding:24px;text-align:center;">
                                    <svg style="width:32px;height:32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span style="font-size:13px;">Klik untuk upload logo</span>
                                    <span style="font-size:12px;color:#cbd5e1;">PNG, SVG, WebP · Transparan direkomendasikan</span>
                                </div>
                                <img id="logoPreview" src="" alt="Logo" style="max-height:100px;max-width:100%;object-fit:contain;padding:12px;display:none;">
                            @endif
                        </label>
                        <button type="button" onclick="document.getElementById('logoInput').click()" id="logoChange" style="{{ $currentLogo ? '' : 'display:none;' }}position:absolute;bottom:8px;right:8px;background:#fff;border:1px solid #e2e8f0;border-radius:6px;padding:4px 10px;font-size:12px;color:#4b5563;cursor:pointer;box-shadow:0 1px 2px rgba(0,0,0,.05);">Ganti</button>
                    </div>
                    <p class="field-hint">Format PNG atau SVG dengan latar belakang transparan disarankan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: HERO --}}
    <div id="panel-hero" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Bagian Hero (Banner Utama)</h3>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label">Gambar Background Hero</label>
                    @php $heroBg = $v('hero_bg'); @endphp
                    <div style="position:relative;">
                        <input type="file" name="hero_bg_file" accept="image/*" id="heroBgInput" style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none;">
                        <label for="heroBgInput" id="heroBgLabel" style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;min-height:140px;border:2px dashed #e2e8f0;border-radius:8px;cursor:pointer;overflow:hidden;transition:border-color .2s;box-sizing:border-box;">
                            @if($heroBg)
                                <div id="heroBgEmpty" style="display:none;"></div>
                                <div style="position:relative;width:100%;height:140px;">
                                    <img id="heroBgPreview" src="{{ Storage::url($heroBg) }}" alt="Hero BG" style="width:100%;height:140px;object-fit:cover;display:block;">
                                    <div style="position:absolute;inset:0;background:rgba(15,23,42,0.55);display:flex;align-items:center;justify-content:center;"><span style="color:#fff;font-size:13px;font-weight:500;">Background Hero saat ini</span></div>
                                </div>
                            @else
                                <div id="heroBgEmpty" style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#94a3b8;padding:24px;text-align:center;">
                                    <svg style="width:32px;height:32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span style="font-size:13px;">Klik untuk upload background hero</span>
                                    <span style="font-size:12px;color:#cbd5e1;">JPG, PNG · 1920×1080 px atau lebih besar</span>
                                </div>
                                <div style="position:relative;width:100%;display:none;" id="heroBgPreviewWrap">
                                    <img id="heroBgPreview" src="" alt="" style="width:100%;height:140px;object-fit:cover;display:block;">
                                    <div style="position:absolute;inset:0;background:rgba(15,23,42,0.55);display:flex;align-items:center;justify-content:center;"><span style="color:#fff;font-size:13px;font-weight:500;">Preview background hero</span></div>
                                </div>
                            @endif
                        </label>
                        <button type="button" onclick="document.getElementById('heroBgInput').click()" id="heroBgChange" style="{{ $heroBg ? '' : 'display:none;' }}position:absolute;bottom:8px;right:8px;background:rgba(255,255,255,0.9);border:1px solid #e2e8f0;border-radius:6px;padding:4px 10px;font-size:12px;color:#4b5563;cursor:pointer;">Ganti</button>
                    </div>
                    <p class="field-hint">Ukuran ideal: 1920×1080 px atau lebih besar.</p>
                </div>
                <div>
                    <label class="field-label">Judul Hero</label>
                    <input type="text" name="hero_title" value="{{ $v('hero_title') }}" class="field-input">
                    <p class="field-hint">Bisa gunakan tag &lt;span&gt; untuk highlight teks.</p>
                </div>
                <div>
                    <label class="field-label">Deskripsi Hero</label>
                    <input type="hidden" name="hero_subtitle" id="hero_subtitle_input" value="{{ $v('hero_subtitle') }}">
                    <div id="hero_subtitle_editor">{!! $v('hero_subtitle') !!}</div>
                </div>
                <div class="set-grid cols-2">
                    <div>
                        <label class="field-label">Teks Tombol Utama</label>
                        <input type="text" name="hero_btn_primary" value="{{ $v('hero_btn_primary') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Teks Tombol Sekunder</label>
                        <input type="text" name="hero_btn_secondary" value="{{ $v('hero_btn_secondary') }}" class="field-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: ABOUT --}}
    <div id="panel-about" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Tentang Perusahaan</h3>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label">Judul About</label>
                    <input type="text" name="about_title" value="{{ $v('about_title') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Deskripsi About</label>
                    <input type="hidden" name="about_desc" id="about_desc_input" value="{{ $v('about_desc') }}">
                    <div id="about_desc_editor">{!! $v('about_desc') !!}</div>
                </div>
                <div>
                    <label class="field-label">Visi <span style="font-weight:400; color:#9ca3af;">(satu poin per baris)</span></label>
                    <textarea name="about_vision" rows="4" class="field-input">{{ $v('about_vision') }}</textarea>
                </div>
                <div>
                    <label class="field-label">Misi <span style="font-weight:400; color:#9ca3af;">(satu poin per baris)</span></label>
                    <textarea name="about_mission" rows="4" class="field-input">{{ $v('about_mission') }}</textarea>
                </div>
                <div>
                    <label class="field-label">4 Keunggulan Perusahaan</label>
                    <div class="set-grid cols-2" style="gap:10px; margin-top:6px;">
                        @for($i = 1; $i <= 4; $i++)
                            <input type="text" name="about_feature_{{ $i }}" value="{{ $v('about_feature_'.$i) }}" class="field-input" placeholder="Keunggulan {{ $i }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="field-label">Sertifikasi (maks. 3)</label>
                    <div class="set-grid cols-3" style="gap:12px; margin-top:6px;">
                        @for($i = 1; $i <= 3; $i++)
                            <div style="border:1px solid #e5e7eb; border-radius:6px; padding:12px; display:flex; flex-direction:column; gap:8px;">
                                <p style="font-size:12px; font-weight:500; color:#6b7280; margin:0;">Sertifikasi {{ $i }}</p>
                                <input type="text" name="about_cert_{{ $i }}" value="{{ $v('about_cert_'.$i) }}" class="field-input" placeholder="ISO 9001:2015">
                                <input type="text" name="about_cert_{{ $i }}_icon" value="{{ $v('about_cert_'.$i.'_icon') }}" class="field-input" placeholder="fa-certificate" style="font-family:monospace;">
                            </div>
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="field-label">Foto / Gambar About</label>
                    @if($v('about_img'))
                        <img src="{{ Storage::url($v('about_img')) }}" style="width:128px; height:80px; object-fit:cover; border-radius:6px; display:block; margin-bottom:8px;" alt="">
                    @endif
                    <input type="file" name="about_img_file" accept="image/*" class="field-input" style="padding:6px;">
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: STATISTIK --}}
    <div id="panel-statistik" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Statistik / Milestone</h3>
            <div class="set-grid cols-4">
                <div>
                    <label class="field-label">Tahun Pengalaman</label>
                    <input type="number" name="milestone_years" value="{{ $v('milestone_years') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Klien Industri Aktif</label>
                    <input type="number" name="milestone_clients" value="{{ $v('milestone_clients') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Varian Produk</label>
                    <input type="number" name="milestone_products" value="{{ $v('milestone_products') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Pengiriman Tepat Waktu (%)</label>
                    <input type="number" name="milestone_delivery" value="{{ $v('milestone_delivery') }}" class="field-input">
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: JUDUL SEKSI + TAMPILKAN/SEMBUNYIKAN --}}
    <div id="panel-seksi" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 6px;">Judul, Deskripsi &amp; Tampilan Tiap Seksi</h3>
            <p style="font-size:13px; color:#6b7280; margin:0 0 20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">
                Atur judul/deskripsi tiap seksi dan gunakan sakelar untuk menampilkan atau menyembunyikannya di halaman utama. Seksi yang belum memiliki data tetap otomatis tersembunyi.
            </p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @php
                    // [showKey, sectionName, titleKey|null, descKey|null]
                    $sections = [
                        ['show_milestone',    'Statistik / Milestone', null,                null],
                        ['show_categories',   'Kategori',              'categories_title',  'categories_desc'],
                        ['show_products',     'Produk',                'products_title',    'products_desc'],
                        ['show_video',        'Video',                 'video_title',       'video_desc'],
                        ['show_cta',          'Banner CTA / E-Catalog', null,               null],
                        ['show_clients',      'Klien / Mitra',         'clients_title',     'clients_desc'],
                        ['show_testimonials', 'Testimoni',             'testimonials_title', null],
                        ['show_faq',          'FAQ',                   'faq_title',         'faq_desc'],
                        ['show_blog',         'Blog',                  'blog_title',        'blog_desc'],
                        ['show_contact',      'Kontak',                null,                null],
                    ];
                @endphp
                @foreach($sections as [$showKey, $sectionName, $titleKey, $descKey])
                    <div style="border:1px solid #f1f5f9; border-radius:8px; padding:14px 16px; background:#fcfcfd;">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
                            <span style="font-size:14px; font-weight:600; color:#1f2937;">{{ $sectionName }}</span>
                            <span class="switch">
                                <input type="hidden" name="{{ $showKey }}" value="0">
                                <input type="checkbox" name="{{ $showKey }}" value="1" {{ $isOn($showKey) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </span>
                        </div>
                        @if($titleKey || $descKey)
                            <div class="set-grid cols-2" style="gap:12px; margin-top:12px;">
                                @if($titleKey)
                                    <div>
                                        <label class="field-label">Judul</label>
                                        <input type="text" name="{{ $titleKey }}" value="{{ $v($titleKey) }}" class="field-input">
                                    </div>
                                @endif
                                @if($descKey)
                                    <div>
                                        <label class="field-label">Deskripsi</label>
                                        <input type="text" name="{{ $descKey }}" value="{{ $v($descKey) }}" class="field-input">
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PANEL: CTA --}}
    <div id="panel-cta" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Banner CTA / E-Catalog</h3>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label">Judul CTA</label>
                    <input type="text" name="cta_title" value="{{ $v('cta_title') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Deskripsi CTA</label>
                    <input type="hidden" name="cta_desc" id="cta_desc_input" value="{{ $v('cta_desc') }}">
                    <div id="cta_desc_editor">{!! $v('cta_desc') !!}</div>
                </div>
                <div>
                    <label class="field-label">File E-Catalog (PDF)</label>
                    @if($v('ecatalog_file'))
                        <p style="font-size:12px; color:#16a34a; margin-bottom:6px;">File tersimpan: <code>{{ $v('ecatalog_file') }}</code></p>
                    @endif
                    <input type="file" name="ecatalog_file_upload" accept=".pdf" class="field-input" style="padding:6px;">
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: KONTAK --}}
    <div id="panel-kontak" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Informasi Kontak</h3>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label">Judul Seksi Kontak</label>
                    <input type="text" name="contact_title" value="{{ $v('contact_title') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Deskripsi Seksi Kontak</label>
                    <input type="hidden" name="contact_desc" id="contact_desc_input" value="{{ $v('contact_desc') }}">
                    <div id="contact_desc_editor">{!! $v('contact_desc') !!}</div>
                </div>
                <div>
                    <label class="field-label">Alamat</label>
                    <textarea name="address" rows="3" class="field-input" style="resize:vertical;">{{ $v('address') }}</textarea>
                </div>
                <div class="set-grid cols-3">
                    <div>
                        <label class="field-label">Telepon</label>
                        <input type="text" name="phone" value="{{ $v('phone') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" value="{{ $v('email') }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Nomor WhatsApp</label>
                        <input type="text" name="wa_number" value="{{ $v('wa_number') }}" class="field-input" placeholder="628xxx" style="font-family:monospace;">
                        <p class="field-hint">Format: 628xxx (tanpa tanda +)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL: SEO --}}
    <div id="panel-seo" class="settings-panel" style="display:none;">
        @php
            $seoTitleVal = $v('seo_title');
            $seoDescVal  = $v('seo_description');
            $checks = [
                ['Judul SEO terisi (30–60 karakter ideal)', mb_strlen($seoTitleVal) >= 20 && mb_strlen($seoTitleVal) <= 65],
                ['Deskripsi SEO terisi (70–160 karakter ideal)', mb_strlen($seoDescVal) >= 70 && mb_strlen($seoDescVal) <= 170],
                ['Kata kunci diisi', trim($v('seo_keywords')) !== ''],
                ['Gambar preview share (Open Graph) diunggah', trim($v('seo_og_image')) !== ''],
                ['Favicon diunggah', trim($v('favicon')) !== ''],
                ['Website boleh diindeks mesin pencari', $v('seo_noindex', '0') !== '1'],
                ['Google Search Console terverifikasi', trim($v('google_site_verification')) !== ''],
                ['Google Analytics terpasang', trim($v('google_analytics_id')) !== ''],
                ['Nama perusahaan, alamat, dan telepon terisi (data bisnis lokal)', trim($v('company_name')) !== '' && trim($v('address')) !== '' && trim($v('phone')) !== ''],
            ];
            $passed = collect($checks)->where(1, true)->count();
        @endphp

        <div class="set-card">
            <h3 class="set-h">Tampilan di Google</h3>
            <p class="set-sub">Pratinjau perkiraan hasil pencarian untuk halaman beranda. Judul dan deskripsi yang terlalu panjang akan dipotong Google.</p>
            <div class="serp">
                <div class="serp-url" id="serpUrl">{{ preg_replace('#^https?://#', '', url('/')) }}</div>
                <div class="serp-title" id="serpTitle"></div>
                <div class="serp-desc" id="serpDesc"></div>
            </div>
        </div>

        <div class="set-card">
            <h3 class="set-h">Meta Beranda</h3>
            <p class="set-sub">Kosongkan untuk memakai nilai bawaan (nama perusahaan + tagline, dan subjudul hero).</p>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label" style="display:flex; justify-content:space-between;">Judul SEO (title) <span class="counter" id="cntTitle"></span></label>
                    <input type="text" name="seo_title" id="seoTitle" value="{{ $seoTitleVal }}" maxlength="70" class="field-input" placeholder="Kata kunci utama | Nama Perusahaan">
                    <p class="field-hint">Taruh kata kunci utama di depan. Ideal 50–60 karakter.</p>
                </div>
                <div>
                    <label class="field-label" style="display:flex; justify-content:space-between;">Deskripsi SEO (meta description) <span class="counter" id="cntDesc"></span></label>
                    <textarea name="seo_description" id="seoDesc" rows="3" maxlength="200" class="field-input" style="resize:vertical;" placeholder="Ringkasan menarik tentang bisnis Anda">{{ $seoDescVal }}</textarea>
                    <p class="field-hint">Ideal 120–160 karakter. Tulis manfaat dan ajakan bertindak, bukan sekadar daftar kata kunci.</p>
                </div>
                <div>
                    <label class="field-label">Kata Kunci (keywords)</label>
                    <input type="text" name="seo_keywords" value="{{ $v('seo_keywords') }}" maxlength="255" class="field-input" placeholder="abrasive industri, grinding wheel, cutting disc">
                    <p class="field-hint">Pisahkan dengan koma. Google tidak memakainya sebagai faktor peringkat, tetapi sebagian mesin pencari lain masih membacanya.</p>
                </div>
            </div>
        </div>

        <div class="set-card">
            <h3 class="set-h">Gambar &amp; Ikon</h3>
            <p class="set-sub">Dipakai saat tautan dibagikan di WhatsApp, Facebook, LinkedIn, dan tab browser.</p>
            <div class="set-grid cols-2">
                <div>
                    <label class="field-label">Gambar Preview Share (Open Graph)</label>
                    @if($v('seo_og_image'))
                        <img src="{{ Storage::url($v('seo_og_image')) }}" alt="" style="width:100%; max-width:300px; aspect-ratio:1200/630; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb; margin-bottom:8px; display:block;">
                        <label style="font-size:12px; color:#dc2626; display:flex; gap:6px; align-items:center; margin-bottom:8px;"><input type="checkbox" name="remove_seo_og_image" value="1"> Hapus gambar ini</label>
                    @endif
                    <input type="file" name="seo_og_image_file" accept="image/*" class="field-input" style="padding:6px;">
                    <p class="field-hint">Ukuran ideal 1200 × 630 px (JPG/PNG, maks 2MB). Jika kosong dipakai gambar hero, lalu logo.</p>
                </div>
                <div>
                    <label class="field-label">Favicon</label>
                    @if($v('favicon'))
                        <img src="{{ Storage::url($v('favicon')) }}" alt="" style="width:48px; height:48px; object-fit:contain; border-radius:8px; border:1px solid #e5e7eb; padding:6px; margin-bottom:8px; display:block;">
                        <label style="font-size:12px; color:#dc2626; display:flex; gap:6px; align-items:center; margin-bottom:8px;"><input type="checkbox" name="remove_favicon" value="1"> Hapus favicon ini</label>
                    @endif
                    <input type="file" name="favicon_file" accept=".png,.ico,.svg,.webp,.jpg,.jpeg" class="field-input" style="padding:6px;">
                    <p class="field-hint">PNG/SVG persegi, minimal 192 × 192 px. Jika kosong dipakai logo perusahaan.</p>
                </div>
            </div>
        </div>

        <div class="set-card">
            <h3 class="set-h">Verifikasi &amp; Analitik</h3>
            <p class="set-sub">Hanya isi kodenya saja, bukan seluruh tag HTML.</p>
            <div class="set-grid cols-3">
                <div>
                    <label class="field-label">Google Search Console</label>
                    <input type="text" name="google_site_verification" value="{{ $v('google_site_verification') }}" class="field-input" placeholder="kode verifikasi" style="font-family:monospace;">
                </div>
                <div>
                    <label class="field-label">Bing Webmaster</label>
                    <input type="text" name="bing_site_verification" value="{{ $v('bing_site_verification') }}" class="field-input" placeholder="kode verifikasi" style="font-family:monospace;">
                </div>
                <div>
                    <label class="field-label">Google Analytics (GA4)</label>
                    <input type="text" name="google_analytics_id" value="{{ $v('google_analytics_id') }}" class="field-input" placeholder="G-XXXXXXXXXX" style="font-family:monospace;">
                </div>
            </div>
        </div>

        <div class="set-card">
            <h3 class="set-h">Indeks Mesin Pencari</h3>
            <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; border:1px solid #f1f5f9; border-radius:8px; padding:14px 16px; background:#fcfcfd;">
                <div>
                    <div style="font-size:14px; font-weight:600; color:#1f2937;">Sembunyikan website dari mesin pencari</div>
                    <div style="font-size:12px; color:#6b7280; margin-top:2px;">Aktifkan hanya saat website masih dalam pengembangan (menambah <code>noindex</code> dan memblokir robots.txt).</div>
                </div>
                <span class="switch">
                    <input type="hidden" name="seo_noindex" value="0">
                    <input type="checkbox" name="seo_noindex" value="1" {{ $v('seo_noindex', '0') === '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </span>
            </div>
            <div style="margin-top:16px; display:grid; grid-template-columns:auto 1fr; gap:6px 14px; font-size:13px; color:#475569;">
                <span style="color:#94a3b8;">Sitemap</span><a href="{{ route('sitemap') }}" target="_blank" style="color:#4f46e5;">{{ route('sitemap') }}</a>
                <span style="color:#94a3b8;">robots.txt</span><a href="{{ route('robots') }}" target="_blank" style="color:#4f46e5;">{{ route('robots') }}</a>
            </div>
            <p class="field-hint" style="margin-top:10px;">Setelah website online, daftarkan URL sitemap di Google Search Console agar halaman produk cepat terindeks.</p>
        </div>

        <div class="set-card">
            <h3 class="set-h" style="display:flex; justify-content:space-between; align-items:center;">Checklist SEO <span style="font-size:12px; font-weight:600; color:{{ $passed === count($checks) ? '#059669' : '#d97706' }};">{{ $passed }}/{{ count($checks) }} terpenuhi</span></h3>
            <ul style="list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:8px;">
                @foreach($checks as [$label, $ok])
                    <li style="display:flex; gap:10px; align-items:center; font-size:13px; color:{{ $ok ? '#334155' : '#92400e' }};">
                        <span style="width:18px; height:18px; border-radius:50%; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; background:{{ $ok ? '#d1fae5' : '#fef3c7' }}; color:{{ $ok ? '#047857' : '#b45309' }};">{{ $ok ? '✓' : '!' }}</span>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>
            <p class="field-hint" style="margin-top:12px;">Halaman produk otomatis memakai judul, deskripsi, gambar, schema <em>Product</em>, dan breadcrumb dari data produk masing-masing.</p>
        </div>
    </div>

    {{-- PANEL: LOKASI & SOSIAL --}}
    <div id="panel-sosial" class="settings-panel" style="display:none;">
        <div class="set-card">
            <h3 class="set-h">Jam Operasional &amp; Lokasi</h3>
            <p class="set-sub">Ditampilkan di bagian kontak dan dipakai sebagai data bisnis lokal untuk Google.</p>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label class="field-label">Jam Operasional</label>
                    <input type="text" name="business_hours" value="{{ $v('business_hours') }}" class="field-input" placeholder="Senin – Jumat, 08.00 – 17.00 WIB">
                </div>
                <div class="set-grid cols-2">
                    <div>
                        <label class="field-label">Kota</label>
                        <input type="text" name="address_city" value="{{ $v('address_city') }}" class="field-input" placeholder="Jakarta Barat">
                    </div>
                    <div>
                        <label class="field-label">Kode Pos</label>
                        <input type="text" name="address_postal" value="{{ $v('address_postal') }}" class="field-input" placeholder="11180">
                    </div>
                </div>
                <div>
                    <label class="field-label">Lokasi di Peta</label>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                        <input type="text" id="mapSearch" class="field-input" style="flex:1; min-width:220px;" placeholder="Cari alamat atau nama tempat, lalu tekan Enter">
                        <button type="button" id="mapSearchBtn" class="map-btn">Cari</button>
                        <button type="button" id="mapUseAddress" class="map-btn" title="Cari berdasarkan alamat di tab Kontak">Pakai alamat perusahaan</button>
                    </div>
                    <div id="mapResults" style="display:none; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:8px; overflow:hidden; background:#fff;"></div>
                    <div id="locationMap" style="width:100%; height:340px; border-radius:10px; border:1px solid #e2e8f0; z-index:0;"></div>
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:10px;">
                        <input type="text" name="maps_lat" id="mapsLat" value="{{ $v('maps_lat') }}" readonly class="field-input" style="width:150px; font-family:monospace; background:#f8fafc;" placeholder="Latitude">
                        <input type="text" name="maps_lng" id="mapsLng" value="{{ $v('maps_lng') }}" readonly class="field-input" style="width:150px; font-family:monospace; background:#f8fafc;" placeholder="Longitude">
                        <button type="button" id="mapClear" class="map-btn" style="color:#dc2626;">Hapus lokasi</button>
                    </div>
                    @error('maps_lat')<p style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
                    <p class="field-hint">Klik peta atau seret pin untuk menentukan titik lokasi. Peta Google ditampilkan otomatis di bagian Kontak website dan titik koordinatnya dikirim ke Google sebagai lokasi bisnis.</p>
                </div>
            </div>
        </div>

        <div class="set-card">
            <h3 class="set-h">Sosial Media</h3>
            <p class="set-sub">Ikon tampil di footer dan dikirim ke Google sebagai profil resmi perusahaan. Kosongkan yang tidak dipakai.</p>
            <div class="set-grid cols-2">
                @foreach([
                    ['social_instagram', 'Instagram', 'https://instagram.com/akunanda'],
                    ['social_facebook',  'Facebook',  'https://facebook.com/halamananda'],
                    ['social_linkedin',  'LinkedIn',  'https://linkedin.com/company/nama'],
                    ['social_youtube',   'YouTube',   'https://youtube.com/@channelanda'],
                    ['social_tiktok',    'TikTok',    'https://tiktok.com/@akunanda'],
                    ['social_twitter',   'X (Twitter)', 'https://x.com/akunanda'],
                ] as [$k, $label, $ph])
                    <div>
                        <label class="field-label">{{ $label }}</label>
                        <input type="url" name="{{ $k }}" value="{{ $v($k) }}" class="field-input" placeholder="{{ $ph }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PANEL: FOOTER --}}
    <div id="panel-footer" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04);">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Footer</h3>
            <div>
                <label class="field-label">Teks Copyright Footer</label>
                <input type="text" name="footer_text" value="{{ $v('footer_text') }}" class="field-input">
            </div>
        </div>
    </div>

    {{-- SAVE BAR --}}
    <div class="save-bar" style="margin-top:20px; display:flex; gap:12px; align-items:center;">
        <button type="submit"
                style="background:#4f46e5; color:#fff; font-size:14px; font-weight:500; padding:10px 24px; border-radius:6px; border:none; cursor:pointer; display:flex; align-items:center; gap:8px;">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Pengaturan
        </button>
        <a href="{{ route('home') }}" target="_blank"
           style="background:#fff; color:#374151; font-size:14px; font-weight:500; padding:10px 20px; border-radius:6px; border:1px solid #d1d5db; text-decoration:none; display:flex; align-items:center; gap:8px;">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Website
        </a>
    </div>
</form>

<style>
    .map-btn { padding:8px 14px; border:1px solid #d1d5db; border-radius:6px; background:#fff; font-size:13px; font-weight:500; color:#374151; cursor:pointer; white-space:nowrap; }
    .map-btn:hover { background:#f8fafc; border-color:#a5b4fc; color:#4f46e5; }
    .map-result { display:block; width:100%; text-align:left; padding:9px 12px; border:0; border-bottom:1px solid #f1f5f9; background:#fff; font-size:13px; color:#334155; cursor:pointer; }
    .map-result:hover { background:#eef2ff; }
    .set-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(15,23,42,.04); margin-bottom:16px; }
    .set-h { font-size:15px; font-weight:600; color:#1f2937; margin:0 0 16px; }
    .set-sub { font-size:13px; color:#6b7280; margin:-10px 0 18px; padding-bottom:14px; border-bottom:1px solid #f3f4f6; }
    .counter { font-size:12px; font-weight:500; color:#94a3b8; }
    .counter.ok { color:#059669; } .counter.warn { color:#d97706; } .counter.bad { color:#dc2626; }
    .serp { border:1px solid #e2e8f0; border-radius:10px; padding:16px 18px; background:#fff; max-width:640px; font-family:Arial, sans-serif; }
    .serp-url { font-size:12px; color:#4d5156; margin-bottom:2px; }
    .serp-title { font-size:19px; line-height:1.3; color:#1a0dab; margin-bottom:3px; overflow-wrap:anywhere; }
    .serp-desc { font-size:13px; line-height:1.55; color:#4d5156; overflow-wrap:anywhere; }
    #settingsForm .save-bar { position:sticky; bottom:0; z-index:50; margin:20px -28px -28px; padding:14px 28px; background:rgba(255,255,255,.92); backdrop-filter:blur(8px); border-top:1px solid #e2e8f0; }
    @media (max-width:768px) { #settingsForm .save-bar { margin:20px -16px -16px; padding:12px 16px; } }
    .field-label { display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px; }
    .field-input { width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; color:#1f2937; box-sizing:border-box; outline:none; }
    .field-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.15); }
    .field-hint { font-size:12px; color:#9ca3af; margin-top:4px; }
    .ck-editor__editable { min-height: 100px !important; }

    /* Grid form responsif — mengecil jadi 1 kolom di layar kecil */
    .set-grid { display:grid; gap:16px; }
    .set-grid.cols-2 { grid-template-columns:1fr 1fr; }
    .set-grid.cols-3 { grid-template-columns:1fr 1fr 1fr; }
    .set-grid.cols-4 { grid-template-columns:repeat(4,1fr); }
    @media (max-width:640px) {
        .set-grid.cols-2, .set-grid.cols-3, .set-grid.cols-4 { grid-template-columns:1fr; }
    }

    /* Toggle switch untuk tampilkan/sembunyikan seksi */
    .switch { position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0; }
    .switch input[type=checkbox] { position:absolute; opacity:0; width:0; height:0; }
    .switch .slider { position:absolute; inset:0; background:#cbd5e1; border-radius:24px; transition:background .2s; }
    .switch .slider::before { content:''; position:absolute; height:18px; width:18px; left:3px; top:3px; background:#fff; border-radius:50%; transition:transform .2s; box-shadow:0 1px 2px rgba(0,0,0,.2); }
    .switch input:checked + .slider { background:#4f46e5; }
    .switch input:checked + .slider::before { transform:translateX(20px); }
    .switch input:focus-visible + .slider { box-shadow:0 0 0 3px rgba(99,102,241,.3); }
</style>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
(function() {
    var allTabs = ['identitas','seo','hero','about','statistik','seksi','cta','kontak','sosial','footer'];

    // CKEditor fields per tab
    var tabCkFields = {
        'hero':   ['hero_subtitle'],
        'about':  ['about_desc'],
        'cta':    ['cta_desc'],
        'kontak': ['contact_desc'],
    };
    var ckEditors = {};

    function showTab(tabId) {
        // Show/hide panels
        allTabs.forEach(function(t) {
            document.getElementById('panel-' + t).style.display = t === tabId ? 'block' : 'none';
        });

        // Update tab button styles
        allTabs.forEach(function(t) {
            var btn = document.getElementById('tab-btn-' + t);
            if (t === tabId) {
                btn.style.color = '#4f46e5';
                btn.style.borderBottomColor = '#4f46e5';
                btn.style.fontWeight = '600';
            } else {
                btn.style.color = '#6b7280';
                btn.style.borderBottomColor = 'transparent';
                btn.style.fontWeight = '500';
            }
        });

        // Initialize CKEditor for fields in this tab (only once)
        var fields = tabCkFields[tabId] || [];
        fields.forEach(function(field) {
            if (!ckEditors[field]) {
                var el = document.getElementById(field + '_editor');
                if (el) {
                    ClassicEditor.create(el, {
                        toolbar: { items: ['bold','italic','|','link','bulletedList','numberedList','|','undo','redo'] },
                        language: 'id',
                    }).then(function(editor) {
                        ckEditors[field] = editor;
                    }).catch(console.error);
                }
            }
        });

        if (tabId === 'sosial' && window.initLocationMap) window.initLocationMap();

        // Save current tab to localStorage
        localStorage.setItem('settings_tab', tabId);
    }

    // Expose globally
    window.showTab = showTab;

    // Sync CKEditor on submit
    document.getElementById('settingsForm').addEventListener('submit', function() {
        Object.keys(ckEditors).forEach(function(field) {
            var input = document.getElementById(field + '_input');
            if (input) input.value = ckEditors[field].getData();
        });
    });

    // Restore last active tab or default to first
    var savedTab = localStorage.getItem('settings_tab');
    var startTab = (savedTab && allTabs.indexOf(savedTab) !== -1) ? savedTab : 'identitas';
    showTab(startTab);
})();


// SEO: pratinjau Google + penghitung karakter
(function () {
    var t = document.getElementById('seoTitle'), d = document.getElementById('seoDesc');
    if (!t || !d) return;
    var fallbackTitle = @json(($settings['company_name'] ?? 'Website') . (!empty($settings['company_tagline']) ? ' | ' . $settings['company_tagline'] : ''));
    var fallbackDesc = @json(trim(strip_tags($settings['hero_subtitle'] ?? '')));
    function cut(s, n) { return s.length > n ? s.slice(0, n - 1).trimEnd() + '…' : s; }
    function counter(el, len, min, max) {
        el.textContent = len + ' karakter';
        el.className = 'counter ' + (len === 0 ? '' : (len >= min && len <= max ? 'ok' : (len > max + 10 ? 'bad' : 'warn')));
    }
    function update() {
        document.getElementById('serpTitle').textContent = cut(t.value.trim() || fallbackTitle, 60);
        document.getElementById('serpDesc').textContent = cut(d.value.trim() || fallbackDesc, 160);
        counter(document.getElementById('cntTitle'), t.value.length, 30, 60);
        counter(document.getElementById('cntDesc'), d.value.length, 70, 160);
    }
    t.addEventListener('input', update); d.addEventListener('input', update); update();
})();


// Pemilih lokasi (Leaflet + OpenStreetMap, tanpa API key)
(function () {
    var map = null, marker = null;
    var latEl = document.getElementById('mapsLat'), lngEl = document.getElementById('mapsLng');
    var results = document.getElementById('mapResults');
    var DEFAULT = [-6.2088, 106.8456]; // Jakarta

    function setPoint(lat, lng, zoom) {
        lat = Math.round(lat * 1e6) / 1e6; lng = Math.round(lng * 1e6) / 1e6;
        latEl.value = lat; lngEl.value = lng;
        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function () { var p = marker.getLatLng(); setPoint(p.lat, p.lng); });
        } else { marker.setLatLng([lat, lng]); }
        if (zoom) map.setView([lat, lng], zoom);
    }
    // Alamat lengkap (lantai, blok, nomor, nama gedung) sering tidak dikenali geocoder.
    // Karena itu dicoba beberapa versi yang makin sederhana sampai ada hasil.
    function clean(t) {
        return t.replace(/\(.*?\)/g, ' ')
            .replace(/\b(Lantai|Lt)\.?\s*[A-Za-z]*\s*\d*/gi, ' ')
            .replace(/\bBlok\s*\S+/gi, ' ')
            .replace(/\b(No|Nomor|Kav)\.?\s*\d+\S*/gi, ' ')
            .replace(/\b(Gedung|Ruko|Komplek|Kompleks)\b/gi, ' ')
            .replace(/\bJl\.?\s*/gi, 'Jalan ')
            .replace(/\s*,\s*/g, ' ').replace(/\s+/g, ' ').trim();
    }
    function variants(q) {
        var lines = q.split(/\n/).map(function (l) { return l.trim(); }).filter(Boolean);
        var full = q.replace(/\s*\n\s*/g, ', ').replace(/\bJl\.?\s*/gi, 'Jalan ');
        var list = lines.length > 1 ? [] : [full];
        lines.forEach(function (l) { list.push(clean(l)); });
        if (lines.length > 1) { list.push(clean(lines[lines.length - 2] + ' ' + lines[lines.length - 1])); list.push(full); }
        var seen = {};
        return list.filter(function (v) { if (!v || v.length < 3 || seen[v.toLowerCase()]) return false; seen[v.toLowerCase()] = 1; return true; }).slice(0, 5);
    }
    function nominatim(q) {
        return fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&accept-language=id&q=' + encodeURIComponent(q))
            .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); });
    }
    function showMessage(html, color) {
        results.style.display = 'block';
        results.innerHTML = '<div style="padding:10px 12px; font-size:13px; color:' + (color || '#94a3b8') + ';">' + html + '</div>';
    }
    var searchToken = 0;
    function search(q) {
        q = (q || '').trim();
        if (!q) return;
        var token = ++searchToken, tries = variants(q), i = 0;
        function next() {
            if (token !== searchToken) return;
            if (i >= tries.length) {
                showMessage('Lokasi tidak ditemukan. Coba ketik nama gedung/jalan saja (mis. "Lindeteves Trade Center"), atau klik langsung di peta.');
                return;
            }
            var attempt = tries[i++];
            showMessage('Mencari: ' + attempt.replace(/</g, '&lt;') + '…');
            nominatim(attempt).then(function (list) {
                if (token !== searchToken) return;
                if (!list.length) { setTimeout(next, 1100); return; } // jeda 1 dtk sesuai kebijakan Nominatim
                results.innerHTML = '';
                if (attempt !== tries[0] || i > 1) {
                    var note = document.createElement('div');
                    note.style.cssText = 'padding:8px 12px; font-size:12px; color:#94a3b8; background:#f8fafc; border-bottom:1px solid #f1f5f9;';
                    note.textContent = 'Alamat lengkap tidak dikenali, menampilkan hasil untuk "' + attempt + '". Pilih yang paling sesuai lalu geser pin bila perlu.';
                    results.appendChild(note);
                }
                list.forEach(function (it) {
                    var b = document.createElement('button');
                    b.type = 'button'; b.className = 'map-result'; b.textContent = it.display_name;
                    b.onclick = function () { setPoint(parseFloat(it.lat), parseFloat(it.lon), 17); results.style.display = 'none'; };
                    results.appendChild(b);
                });
            }).catch(function () {
                if (token !== searchToken) return;
                showMessage('Gagal menghubungi layanan pencarian lokasi. Periksa koneksi internet, atau klik langsung di peta.', '#dc2626');
            });
        }
        next();
    }

    window.initLocationMap = function () {
        if (map) { setTimeout(function () { map.invalidateSize(); }, 50); return; }
        if (typeof L === 'undefined') {
            document.getElementById('locationMap').innerHTML = '<p style="padding:20px; font-size:13px; color:#dc2626;">Peta gagal dimuat (library Leaflet tidak terunduh). Periksa koneksi internet lalu muat ulang halaman.</p>';
            return;
        }
        var has = latEl.value && lngEl.value;
        var start = has ? [parseFloat(latEl.value), parseFloat(lngEl.value)] : DEFAULT;
        map = L.map('locationMap').setView(start, has ? 17 : 11);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
        if (has) setPoint(start[0], start[1]);
        map.on('click', function (e) { setPoint(e.latlng.lat, e.latlng.lng); results.style.display = 'none'; });
        setTimeout(function () { map.invalidateSize(); }, 100);
    };

    document.getElementById('mapSearchBtn').onclick = function () { search(document.getElementById('mapSearch').value); };
    document.getElementById('mapSearch').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); search(this.value); } });
    document.getElementById('mapUseAddress').onclick = function () {
        var a = (document.querySelector('[name="address"]').value || '').trim();
        document.getElementById('mapSearch').value = a;
        search(a);
    };
    document.getElementById('mapClear').onclick = function () {
        latEl.value = ''; lngEl.value = '';
        if (marker) { map.removeLayer(marker); marker = null; }
    };

    // Tab "Lokasi & Sosial" bisa sudah terbuka saat halaman dimuat (tab terakhir diingat browser),
    // sedangkan showTab() berjalan sebelum fungsi ini ada → inisialisasi peta di sini.
    if (document.getElementById('panel-sosial').style.display === 'block') window.initLocationMap();
})();

// Hero background preview
document.getElementById('heroBgInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('heroBgPreview');
        var wrap = document.getElementById('heroBgPreviewWrap');
        preview.src = e.target.result;
        if (wrap) wrap.style.display = '';
        document.getElementById('heroBgEmpty').style.display = 'none';
        document.getElementById('heroBgChange').style.display = '';
    };
    reader.readAsDataURL(file);
});

// Logo preview
document.getElementById('logoInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logoPreview').src = e.target.result;
        document.getElementById('logoPreview').style.display = '';
        document.getElementById('logoEmpty').style.display = 'none';
        document.getElementById('logoChange').style.display = '';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
