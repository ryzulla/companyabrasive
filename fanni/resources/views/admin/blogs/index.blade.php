@extends('admin.layouts.app')

@section('title', 'Blog')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $blogs->total() }} artikel
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Artikel
    </a>
</div>

<form method="GET" action="{{ route('admin.blogs.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau meta..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.blogs.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($blogs->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="text-sm">{{ request('q') ? 'Tidak ada artikel yang cocok.' : 'Belum ada artikel' }}</p>
        </div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[500px] admin-table">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Gambar</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Judul</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Tanggal</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($blogs as $blog)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3 cell-media">
                            @if($blog->img)
                                <img src="{{ Storage::url($blog->img) }}" class="w-16 h-10 object-cover rounded-lg" alt="">
                            @else
                                <div class="w-16 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3" data-label="Judul">
                            <div class="font-medium text-slate-700">{{ $blog->title }}</div>
                            <div class="text-slate-400 text-xs">{{ $blog->meta }}</div>
                        </td>
                        <td class="px-6 py-3 text-slate-500" data-label="Tanggal">{{ $blog->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-right cell-actions">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @if($blogs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $blogs->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
