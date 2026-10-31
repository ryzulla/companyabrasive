@extends('home.layouts.inner')

@section('seo')
    @include('home._seo', ['page' => [
        'title'       => 'Blog & Panduan | ' . ($s['company_name'] ?? 'Company Profile'),
        'description' => $s['blog_desc'] ?? 'Panduan memilih dan menggunakan amplas, batu gerinda, dan produk abrasive lainnya.',
        'canonical'   => route('blog.index'),
    ]])
@endsection

@section('nav-blog', 'active')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span style="color: #fff;">Blog</span>
            </div>
            <h1>{{ $s['blog_title'] ?? 'Blog' }}</h1>
            <p>{{ strip_tags($s['blog_desc'] ?? '') }}</p>
        </div>
    </section>

    <section class="container section-padding" style="padding: 60px 20px 80px;">
        @if($blogs->isEmpty())
            <p style="text-align:center; color:#64748b;">Belum ada artikel.</p>
        @else
            <div class="blog-grid">
                @foreach($blogs as $blog)
                    <a href="{{ route('blog.show', $blog) }}" class="blog-card">
                        <div class="blog-img" style="background-image: url('{{ $blog->img ? Storage::url($blog->img) : 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80' }}')"></div>
                        <div class="blog-content">
                            <div class="blog-meta">{{ $blog->meta }}</div>
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ $blog->excerpt(120) }}</p>
                            <span class="read-more">Baca Artikel <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($blogs->hasPages())
                <nav class="pager" aria-label="Halaman artikel">
                    @if($blogs->onFirstPage())<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else<a href="{{ $blogs->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></a>@endif

                    @for($i = 1; $i <= $blogs->lastPage(); $i++)
                        @if($i === $blogs->currentPage())<span class="current">{{ $i }}</span>
                        @else<a href="{{ $blogs->url($i) }}">{{ $i }}</a>@endif
                    @endfor

                    @if($blogs->hasMorePages())<a href="{{ $blogs->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></a>
                    @else<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>@endif
                </nav>
            @endif
        @endif
    </section>
@endsection
