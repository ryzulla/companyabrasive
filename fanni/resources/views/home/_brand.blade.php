{{-- Logo + nama perusahaan (navbar). Logo opsional; tagline tampil kecil di bawah nama. --}}
@php
    $brandName  = trim($s['company_name'] ?? 'Company');
    $brandFirst = explode(' ', $brandName)[0];
    $brandRest  = trim(implode(' ', array_slice(explode(' ', $brandName), 1)));
@endphp
<style>
    .brand-wrap { display: flex; align-items: center; gap: 10px; min-width: 0; line-height: 1; }
    .brand-wrap img.brand-img { height: 42px; width: auto; flex-shrink: 0; object-fit: contain; display: block; }
    .brand-wrap i.brand-icon { color: var(--accent); font-size: 1.5rem; }
    .brand-text { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
    .brand-text b { font-size: 1rem; font-weight: 700; color: var(--primary); white-space: nowrap; letter-spacing: -0.1px; }
    .brand-text b em { font-style: normal; color: var(--accent); }
    .brand-text small { font-size: 0.6rem; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: #94a3b8; white-space: nowrap; }
    @media (max-width: 480px) {
        .brand-wrap img.brand-img { height: 34px; }
        .brand-text b { font-size: 0.86rem; }
        .brand-text small { font-size: 0.52rem; letter-spacing: 0.9px; }
    }
</style>
<span class="brand-wrap">
    @if(!empty($s['company_logo']))
        <img class="brand-img" src="{{ Storage::url($s['company_logo']) }}" alt="Logo {{ $brandName }}">
    @else
        <i class="fa-solid {{ $s['company_logo_icon'] ?? 'fa-gear' }} brand-icon"></i>
    @endif
    <span class="brand-text">
        <b>{{ $brandFirst }} <em>{{ $brandRest }}</em></b>
        @if(!empty($s['company_tagline']))<small>{{ $s['company_tagline'] }}</small>@endif
    </span>
</span>
