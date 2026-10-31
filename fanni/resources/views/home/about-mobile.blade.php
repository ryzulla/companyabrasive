@extends('home.layouts.mobile-page')

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

@section('tab-about', 'active')

@section('content')
    @include('home._m_about', ['standalone' => true])
    @include('home._social')
@endsection

@push('scripts')
<script>
    // Hitung naik angka statistik
    document.querySelectorAll('.counter').forEach(function (c) {
        var target = +c.getAttribute('data-target'), n = 0, step = Math.max(1, target / 60);
        (function tick() { n = Math.min(target, n + step); c.textContent = Math.ceil(n); if (n < target) requestAnimationFrame(tick); })();
    });
</script>
@endpush
