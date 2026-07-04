@extends('admin.layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $faqs->total() }} FAQ
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
    <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah FAQ
    </a>
</div>

<form method="GET" action="{{ route('admin.faqs.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pertanyaan atau jawaban..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.faqs.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="space-y-3">
    @forelse($faqs as $faq)
        <div class="bg-white rounded-xl border border-slate-100 p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-slate-700 text-sm">{{ $faq->question }}</p>
                    <p class="text-slate-500 text-sm mt-1 line-clamp-2">{{ $faq->answer }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 text-center py-16 text-slate-400">
            <p class="text-sm">{{ request('q') ? 'Tidak ada FAQ yang cocok.' : 'Belum ada FAQ' }}</p>
        </div>
    @endforelse

    @if($faqs->hasPages())
        <div class="bg-white rounded-xl border border-slate-100 px-6 py-4">
            {{ $faqs->links() }}
        </div>
    @endif
</div>
@endsection
