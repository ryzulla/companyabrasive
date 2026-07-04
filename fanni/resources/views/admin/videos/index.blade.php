@extends('admin.layouts.app')

@section('title', 'Video')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $videos->total() }} video
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
    <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Video
    </a>
</div>

<form method="GET" action="{{ route('admin.videos.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau deskripsi..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.videos.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($videos->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <p class="text-sm">{{ request('q') ? 'Tidak ada video yang cocok.' : 'Belum ada video' }}</p>
        </div>
    @else
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[500px]">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Thumbnail</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">YouTube ID</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Judul</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($videos as $video)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3">
                            <img src="https://img.youtube.com/vi/{{ $video->id }}/mqdefault.jpg" class="w-20 h-12 object-cover rounded-lg" alt="">
                        </td>
                        <td class="px-6 py-3">
                            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono">{{ $video->id }}</span>
                        </td>
                        <td class="px-6 py-3 font-medium text-slate-700">{{ $video->title }}</td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.videos.edit', $video) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" onsubmit="return confirm('Hapus video ini?')">
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
        @if($videos->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $videos->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
