@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-800">Halo, {{ Auth::user()->name }} 👋</h1>
    <p class="text-sm text-slate-500 mt-0.5">Ringkasan konten website company profile Anda.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            ['label' => 'Kategori', 'value' => $stats['categories'], 'route' => 'admin.categories.index', 'bg' => 'bg-blue-50', 'bghover' => 'group-hover:bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['label' => 'Produk', 'value' => $stats['products'], 'route' => 'admin.products.index', 'bg' => 'bg-indigo-50', 'bghover' => 'group-hover:bg-indigo-100', 'text' => 'text-indigo-600', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Video', 'value' => $stats['videos'], 'route' => 'admin.videos.index', 'bg' => 'bg-purple-50', 'bghover' => 'group-hover:bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ['label' => 'FAQ', 'value' => $stats['faqs'], 'route' => 'admin.faqs.index', 'bg' => 'bg-cyan-50', 'bghover' => 'group-hover:bg-cyan-100', 'text' => 'text-cyan-600', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Testimoni', 'value' => $stats['testimonials'], 'route' => 'admin.testimonials.index', 'bg' => 'bg-teal-50', 'bghover' => 'group-hover:bg-teal-100', 'text' => 'text-teal-600', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Blog', 'value' => $stats['blogs'], 'route' => 'admin.blogs.index', 'bg' => 'bg-orange-50', 'bghover' => 'group-hover:bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['label' => 'Klien / Mitra', 'value' => $stats['clients'], 'route' => 'admin.clients.index', 'bg' => 'bg-amber-50', 'bghover' => 'group-hover:bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Pesan Masuk', 'value' => $stats['messages'], 'route' => 'admin.contacts.index', 'bg' => 'bg-rose-50', 'bghover' => 'group-hover:bg-rose-100', 'text' => 'text-rose-600', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ];
    @endphp

    @foreach($cards as $card)
        @continue(!auth()->user()->isSuperuser() && in_array($card['route'], ['admin.categories.index', 'admin.clients.index']))
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

<div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
    <div class="lg:col-span-3 bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700">Pesan Terbaru</h3>
            <a href="{{ route('admin.contacts.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Lihat semua</a>
        </div>
        @forelse($recentMessages as $m)
            <a href="{{ route('admin.contacts.show', $m) }}" class="flex items-start gap-3 px-5 py-3.5 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold shrink-0">{{ strtoupper(substr($m->name, 0, 1)) }}</div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $m->name }}</p>
                        <span class="text-xs text-slate-400 shrink-0">{{ $m->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate">{{ $m->message }}</p>
                </div>
            </a>
        @empty
            <p class="text-sm text-slate-400 text-center py-10">Belum ada pesan masuk.</p>
        @endforelse
    </div>

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-xl border border-slate-100 p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-2.5">
                <a href="{{ route('admin.products.create') }}" class="text-center text-sm font-medium text-slate-600 border border-slate-200 rounded-lg py-2.5 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">+ Produk</a>
                <a href="{{ route('admin.blogs.create') }}" class="text-center text-sm font-medium text-slate-600 border border-slate-200 rounded-lg py-2.5 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">+ Artikel</a>
                <a href="{{ route('admin.videos.create') }}" class="text-center text-sm font-medium text-slate-600 border border-slate-200 rounded-lg py-2.5 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">+ Video</a>
                @if(auth()->user()->isSuperuser())
                <a href="{{ route('admin.settings.index') }}" class="text-center text-sm font-medium text-slate-600 border border-slate-200 rounded-lg py-2.5 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">Pengaturan</a>
                @else
                <a href="{{ route('admin.contacts.index') }}" class="text-center text-sm font-medium text-slate-600 border border-slate-200 rounded-lg py-2.5 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">Pesan Masuk</a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-700">Produk Terbaru</h3>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Lihat semua</a>
            </div>
            @forelse($recentProducts as $p)
                <a href="{{ route('admin.products.edit', $p) }}" class="flex items-center gap-3 px-5 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                    @if($p->img)
                        <img src="{{ Storage::url($p->img) }}" class="w-10 h-10 rounded-lg object-cover shrink-0" alt="">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-slate-100 shrink-0"></div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $p->title }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $p->category?->title ?? $p->category_id }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-400 text-center py-8">Belum ada produk.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
