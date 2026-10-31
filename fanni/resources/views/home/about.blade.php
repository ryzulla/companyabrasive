@extends('home.layouts.inner')

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => 'Tentang Kami | ' . ($s['company_name'] ?? 'Company Profile'),
        'description' => ($s['about_title'] ?? '') . '. ' . strip_tags($s['about_desc'] ?? ''),
        'canonical'   => route('about'),
        'jsonld'      => [[
            '@context' => 'https://schema.org', '@type' => 'AboutPage', 'name' => 'Tentang Kami',
            'url' => route('about'), 'isPartOf' => ['@type' => 'WebSite', 'url' => url('/')],
        ]],
    ]])
@endsection

@section('active', 'about')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/home-inner.css') }}?v={{ filemtime(public_path('css/home-inner.css')) }}">
@endpush

@section('wa-text', 'Halo%2C+saya+ingin+bertanya+tentang+perusahaan+Anda')

@section('content')
    {{-- PAGE HEADER --}}
    <section class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="bc-current">Tentang Kami</span>
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

    @if(!empty($s['about_vision']) || !empty($s['about_mission']))
    {{-- VISI & MISI --}}
    <section class="section-padding pt-0">
        <div class="container">
            <div class="vm-grid">
                @foreach(['about_vision' => ['Visi', 'fa-eye'], 'about_mission' => ['Misi', 'fa-bullseye']] as $key => [$label, $icon])
                    @php $items = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $s[$key] ?? '')))); @endphp
                    @if($items)
                    <div class="vm-card">
                        <h3><i class="fa-solid {{ $icon }}"></i> {{ $label }}</h3>
                        <ul>
                            @foreach($items as $item)<li>{{ $item }}</li>@endforeach
                        </ul>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
            <p>{{ strip_tags($s['cta_desc'] ?? 'Diskusikan kebutuhan Anda bersama tim kami dan temukan solusi terbaik.') }}</p>
            <div class="cta-buttons">
                <a href="{{ route('products.index') }}" class="btn-primary"><i class="fa-solid fa-box-open"></i> Lihat Produk Kami</a>
                <a href="{{ route('home') }}#contact" class="btn-outline btn-outline-light"><i class="fa-solid fa-paper-plane"></i> Hubungi Kami</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home-about.js') }}?v={{ filemtime(public_path('js/home-about.js')) }}"></script>
@endpush
