@extends('admin.layouts.app')

@section('title', 'Testimoni')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $testimonials->total() }} testimoni
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Testimoni
    </a>
</div>

<form method="GET" action="{{ route('admin.testimonials.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau isi testimoni..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.testimonials.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    @forelse($testimonials as $testimonial)
        <div class="bg-white rounded-xl border border-slate-100 p-5">
            <p class="text-slate-600 text-sm italic mb-4 leading-relaxed">"{{ $testimonial->text }}"</p>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-700 text-sm">{{ $testimonial->author }}</p>
                    <p class="text-slate-400 text-xs">{{ $testimonial->pos }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-2 bg-white rounded-xl border border-slate-100 text-center py-16 text-slate-400">
            <p class="text-sm">{{ request('q') ? 'Tidak ada testimoni yang cocok.' : 'Belum ada testimoni' }}</p>
        </div>
    @endforelse
</div>

@if($testimonials->hasPages())
    <div class="mt-4 bg-white rounded-xl border border-slate-100 px-6 py-4">
        {{ $testimonials->links() }}
    </div>
@endif
@endsection
