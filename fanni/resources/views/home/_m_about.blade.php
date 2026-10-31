{{-- Isi halaman Tentang versi mobile. Dipakai di layar Tentang (beranda) dan halaman /tentang. $standalone: true bila halaman terpisah. --}}
@php
    $showMilestone = ($s['show_milestone'] ?? '1') !== '0';
    $hasCert = collect([1, 2, 3])->contains(fn($i) => !empty($s['about_cert_'.$i]));
    $vm = [];
    foreach (['about_vision' => ['Visi', 'fa-eye'], 'about_mission' => ['Misi', 'fa-bullseye']] as $key => [$label, $icon]) {
        $items = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $s[$key] ?? ''))));
        if ($items) $vm[] = [$label, $icon, $items];
    }
@endphp
<div class="pad">
    <div class="about-img">
        <img src="{{ !empty($s['about_img']) ? Storage::url($s['about_img']) : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80' }}" alt="Tentang Kami">
    </div>
    <div class="about-text">
        @if($standalone ?? false)<h1>{{ $s['about_title'] ?? 'Tentang Kami' }}</h1>@else<h3>{{ $s['about_title'] ?? 'Tentang Kami' }}</h3>@endif
        <div class="about-desc">{!! $s['about_desc'] ?? '' !!}</div>
        <div class="feat-list">
            @foreach([1,2,3,4] as $i)
                @if(!empty($s['about_feature_'.$i]))
                    <div class="feat-item"><i class="fa-solid fa-circle-check"></i> {{ $s['about_feature_'.$i] }}</div>
                @endif
            @endforeach
        </div>
        @if($hasCert)
        <div class="certs">
            @foreach([1,2,3] as $i)
                @if(!empty($s['about_cert_'.$i]))
                    <span class="cert"><i class="fa-solid {{ $s['about_cert_'.$i.'_icon'] ?? 'fa-certificate' }}"></i> {{ $s['about_cert_'.$i] }}</span>
                @endif
            @endforeach
        </div>
        @endif
    </div>
</div>

@foreach($vm as [$label, $icon, $items])
    <div class="pad pad-t0">
        <div class="vm-card">
            <h4><i class="fa-solid {{ $icon }}"></i> {{ $label }}</h4>
            <ul>@foreach($items as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
    </div>
@endforeach

@if($showMilestone)
<div class="stats-strip stats-strip--card">
    <div class="stat-box"><i class="fa-solid fa-calendar-check"></i><h3><span class="counter" data-target="{{ $s['milestone_years'] ?? 0 }}">0</span>+</h3><p>Tahun Pengalaman</p></div>
    <div class="stat-box"><i class="fa-solid fa-building-user"></i><h3><span class="counter" data-target="{{ $s['milestone_clients'] ?? 0 }}">0</span>+</h3><p>Klien Aktif</p></div>
    <div class="stat-box"><i class="fa-solid fa-box-open"></i><h3><span class="counter" data-target="{{ $s['milestone_products'] ?? 0 }}">0</span>+</h3><p>Varian Produk</p></div>
    <div class="stat-box"><i class="fa-solid fa-truck-fast"></i><h3><span class="counter" data-target="{{ $s['milestone_delivery'] ?? 0 }}">0</span>%</h3><p>Tepat Waktu</p></div>
</div>
@endif

<div class="pad pad-t18">
    @if($standalone ?? false)
        <a href="{{ route('home') }}#contact" class="btn btn-primary btn-block mb-10"><i class="fa-solid fa-paper-plane"></i> Hubungi Kami</a>
    @else
        <button class="btn btn-primary btn-block mb-10" onclick="showScreen('contact')"><i class="fa-solid fa-paper-plane"></i> Hubungi Kami</button>
    @endif
    @if(!empty($s['wa_number']))
        <a href="https://wa.me/{{ $s['wa_number'] }}?text=Halo%2C+saya+ingin+bertanya+tentang+perusahaan+Anda" target="_blank" rel="noopener" class="btn btn-wa btn-block"><i class="fa-brands fa-whatsapp"></i> Chat via WhatsApp</a>
    @endif
</div>
