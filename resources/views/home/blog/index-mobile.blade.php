@extends('home.layouts.mobile-page')

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => 'Blog & Panduan | ' . ($s['company_name'] ?? 'Company Profile'),
        'description' => $s['blog_desc'] ?? 'Panduan memilih dan menggunakan amplas, batu gerinda, dan produk abrasive lainnya.',
        'canonical'   => route('blog.index'),
    ]])
@endsection

@section('content')
    <div class="pad pad-b6">
        <h1 class="sec-title">{{ $s['blog_title'] ?? 'Blog' }}</h1>
        <div class="sec-sub">{{ strip_tags($s['blog_desc'] ?? '') }}</div>
    </div>
    <div class="pad pad-t6">
        @forelse($blogs as $blog)
            <a href="{{ route('blog.show', $blog) }}" class="blog-card">
                <div class="blog-thumb" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=300&q=80' }}');"></div>
                <div class="blog-info">
                    <span class="bm">{{ $blog->meta }}</span>
                    <h4>{{ $blog->title }}</h4>
                    <p>{{ $blog->excerpt(80) }}</p>
                </div>
            </a>
        @empty
            <p class="empty-note">Belum ada artikel.</p>
        @endforelse

        @if($blogs->hasPages())
            <div class="pg-row">
                @if($blogs->onFirstPage())<span class="pg-btn disabled">‹ Sebelumnya</span>@else<a class="pg-btn" href="{{ $blogs->previousPageUrl() }}" rel="prev">‹ Sebelumnya</a>@endif
                <span class="pg-info">{{ $blogs->currentPage() }} / {{ $blogs->lastPage() }}</span>
                @if($blogs->hasMorePages())<a class="pg-btn" href="{{ $blogs->nextPageUrl() }}" rel="next">Berikutnya ›</a>@else<span class="pg-btn disabled">Berikutnya ›</span>@endif
            </div>
        @endif
    </div>
@endsection
