{{--
    Head SEO terpusat. Semua nilai bawaan diambil dari Pengaturan > SEO.
    Parameter ($page): title, description, canonical, image, og_type, robots, keywords, jsonld (array), org (bool)
--}}
@php
    use Illuminate\Support\Str;

    $page      = $page ?? [];
    $company   = $s['company_name'] ?? 'Company Profile';
    $plain     = fn($t) => trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace('<', ' <', (string) $t)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

    $title       = $page['title'] ?? (($s['seo_title'] ?? '') ?: $company . (!empty($s['company_tagline']) ? ' | ' . $s['company_tagline'] : ''));
    $description = $plain($page['description'] ?? (($s['seo_description'] ?? '') ?: ($s['hero_subtitle'] ?? '')));
    $description = Str::limit($description, 160, '…');
    $keywords    = $page['keywords'] ?? ($s['seo_keywords'] ?? '');
    $canonical   = $page['canonical'] ?? url()->current();
    $ogType      = $page['og_type'] ?? 'website';

    $absUrl = function ($path) {
        if (!$path) return null;
        return Str::startsWith($path, ['http://', 'https://']) ? $path : url($path);
    };
    $imgPath = $page['image']
        ?? (!empty($s['seo_og_image']) ? Storage::url($s['seo_og_image'])
        : (!empty($s['hero_bg']) ? Storage::url($s['hero_bg'])
        : (!empty($s['company_logo']) ? Storage::url($s['company_logo']) : null)));
    $image = $absUrl($imgPath);

    $noindexSite = ($s['seo_noindex'] ?? '0') === '1';
    $robots = $noindexSite
        ? 'noindex, nofollow'
        : ($page['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');

    $iconPath = $s['favicon'] ?? ($s['company_logo'] ?? null);
    $iconUrl  = $iconPath ? Storage::url($iconPath) : null;

    $socials = collect(['social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_tiktok', 'social_twitter'])
        ->map(fn($k) => $s[$k] ?? null)->filter()->values()->all();

    $jsonld = $page['jsonld'] ?? [];
    if (($page['org'] ?? false) === true) {
        $addr = trim(preg_replace('/\s*\n\s*/', ', ', (string) ($s['address'] ?? '')));
        $org = array_filter([
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            '@id'         => url('/') . '#organization',
            'name'        => $s['company_full_name'] ?? $company,
            'alternateName' => $company,
            'url'         => url('/'),
            'logo'        => $absUrl(!empty($s['company_logo']) ? Storage::url($s['company_logo']) : null),
            'image'       => $image,
            'description' => $description ?: null,
            'email'       => $s['email'] ?? null,
            'telephone'   => $s['phone'] ?? null,
            'address'     => $addr ? array_filter([
                '@type'           => 'PostalAddress',
                'streetAddress'   => $addr,
                'addressLocality' => $s['address_city'] ?? null,
                'postalCode'      => $s['address_postal'] ?? null,
                'addressCountry'  => 'ID',
            ]) : null,
            'geo' => (!empty($s['maps_lat']) && !empty($s['maps_lng'])) ? [
                '@type' => 'GeoCoordinates', 'latitude' => (float) $s['maps_lat'], 'longitude' => (float) $s['maps_lng'],
            ] : null,
            'contactPoint' => array_filter([
                '@type'             => 'ContactPoint',
                'contactType'       => 'sales',
                'telephone'         => !empty($s['wa_number']) ? '+' . ltrim($s['wa_number'], '+') : ($s['phone'] ?? null),
                'email'             => $s['email'] ?? null,
                'areaServed'        => 'ID',
                'availableLanguage' => ['id', 'en'],
            ]),
            'sameAs' => $socials ?: null,
        ]);
        $site = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $company,
            'url'      => url('/'),
            'inLanguage' => 'id-ID',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => route('products.index') . '?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
        array_unshift($jsonld, $org, $site);
    }
@endphp
<title>{{ $title }}</title>
@if($description)<meta name="description" content="{{ $description }}">@endif
@if($keywords)<meta name="keywords" content="{{ $keywords }}">@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" hreflang="id" href="{{ $canonical }}">
<meta name="author" content="{{ $s['company_full_name'] ?? $company }}">
@if($iconUrl)
<link rel="icon" href="{{ $iconUrl }}">
<link rel="apple-touch-icon" href="{{ $iconUrl }}">
@endif
@if(!empty($s['google_site_verification']))<meta name="google-site-verification" content="{{ $s['google_site_verification'] }}">@endif
@if(!empty($s['bing_site_verification']))<meta name="msvalidate.01" content="{{ $s['bing_site_verification'] }}">@endif

<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $company }}">
<meta property="og:title" content="{{ $title }}">
@if($description)<meta property="og:description" content="{{ $description }}">@endif
<meta property="og:url" content="{{ $canonical }}">
@if($image)<meta property="og:image" content="{{ $image }}">@endif
<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $title }}">
@if($description)<meta name="twitter:description" content="{{ $description }}">@endif
@if($image)<meta name="twitter:image" content="{{ $image }}">@endif

@foreach($jsonld as $block)
<script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endforeach

@if(!empty($s['google_analytics_id']) && !$noindexSite)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $s['google_analytics_id'] }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config',@json($s['google_analytics_id']));</script>
@endif
