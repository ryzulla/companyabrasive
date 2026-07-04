@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Kategori', 'value' => $stats['categories'], 'route' => 'admin.categories.index', 'bg' => 'bg-blue-50', 'bghover' => 'group-hover:bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['label' => 'Produk', 'value' => $stats['products'], 'route' => 'admin.products.index', 'bg' => 'bg-indigo-50', 'bghover' => 'group-hover:bg-indigo-100', 'text' => 'text-indigo-600', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Video', 'value' => $stats['videos'], 'route' => 'admin.videos.index', 'bg' => 'bg-purple-50', 'bghover' => 'group-hover:bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ['label' => 'FAQ', 'value' => $stats['faqs'], 'route' => 'admin.faqs.index', 'bg' => 'bg-cyan-50', 'bghover' => 'group-hover:bg-cyan-100', 'text' => 'text-cyan-600', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Testimoni', 'value' => $stats['testimonials'], 'route' => 'admin.testimonials.index', 'bg' => 'bg-teal-50', 'bghover' => 'group-hover:bg-teal-100', 'text' => 'text-teal-600', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Blog', 'value' => $stats['blogs'], 'route' => 'admin.blogs.index', 'bg' => 'bg-orange-50', 'bghover' => 'group-hover:bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['label' => 'Pesan Masuk', 'value' => $stats['messages'], 'route' => 'admin.contacts.index', 'bg' => 'bg-rose-50', 'bghover' => 'group-hover:bg-rose-100', 'text' => 'text-rose-600', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ];
    @endphp

    @foreach($cards as $card)
        <a href="{{ route($card['route']) }}" class="bg-white rounded-xl p-5 border border-slate-100 hover:shadow-md transition group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $card['value'] }}</p>
                </div>
                <div class="w-10 h-10 {{ $card['bg'] }} {{ $card['bghover'] }} rounded-lg flex items-center justify-center transition">
                    <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-slate-100 p-6">
    <h3 class="font-semibold text-slate-700 mb-1">Selamat Datang!</h3>
    <p class="text-sm text-slate-500">Gunakan menu di sidebar untuk mengelola konten website company profile.</p>
</div>
@endsection
