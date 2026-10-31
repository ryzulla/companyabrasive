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
<script src="{{ asset('js/home-about-mobile.js') }}?v={{ filemtime(public_path('js/home-about-mobile.js')) }}"></script>
@endpush
