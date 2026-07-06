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
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:20px; overflow:hidden;">
        <div style="display:flex; overflow-x:auto; border-bottom:1px solid #e5e7eb;">
            @php
                $tabs = [
                    ['id' => 'identitas', 'label' => 'Identitas'],
                    ['id' => 'hero',      'label' => 'Hero'],
                    ['id' => 'about',     'label' => 'Tentang'],
                    ['id' => 'statistik', 'label' => 'Statistik'],
                    ['id' => 'seksi',     'label' => 'Judul Seksi'],
                    ['id' => 'cta',       'label' => 'CTA'],
                    ['id' => 'kontak',    'label' => 'Kontak'],
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
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

    {{-- PANEL: FOOTER --}}
    <div id="panel-footer" class="settings-panel" style="display:none;">
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
            <h3 style="font-size:15px; font-weight:600; color:#1f2937; margin:0 0 20px; padding-bottom:12px; border-bottom:1px solid #f3f4f6;">Footer</h3>
            <div>
                <label class="field-label">Teks Copyright Footer</label>
                <input type="text" name="footer_text" value="{{ $v('footer_text') }}" class="field-input">
            </div>
        </div>
    </div>

    {{-- SAVE BAR --}}
    <div style="margin-top:20px; display:flex; gap:12px; align-items:center;">
        <button type="submit"
                style="background:#2563eb; color:#fff; font-size:14px; font-weight:500; padding:10px 24px; border-radius:6px; border:none; cursor:pointer; display:flex; align-items:center; gap:8px;">
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
    .field-label { display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px; }
    .field-input { width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; color:#1f2937; box-sizing:border-box; outline:none; }
    .field-input:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.15); }
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
    .switch input:checked + .slider { background:#2563eb; }
    .switch input:checked + .slider::before { transform:translateX(20px); }
    .switch input:focus-visible + .slider { box-shadow:0 0 0 3px rgba(59,130,246,.3); }
</style>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
(function() {
    var allTabs = ['identitas','hero','about','statistik','seksi','cta','kontak','footer'];

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
                btn.style.color = '#2563eb';
                btn.style.borderBottomColor = '#2563eb';
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
