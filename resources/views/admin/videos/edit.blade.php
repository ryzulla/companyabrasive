@extends('admin.layouts.app')

@section('title', 'Edit Video')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.videos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.videos.update', $video) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Preview Video</label>
                <div id="videoPreview" style="position:relative; width:100%; max-width:720px; aspect-ratio:16/9; background:#0f172a; border-radius:12px; overflow:hidden;">
                    <iframe id="videoFrame" src="https://www.youtube-nocookie.com/embed/{{ old('id', $video->id) }}" title="{{ $video->title }}" style="position:absolute; inset:0; width:100%; height:100%; border:0;" loading="lazy" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">YouTube Video ID <span class="text-red-500">*</span></label>
                <input type="text" name="id" id="videoIdInput" value="{{ old('id', $video->id) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
                <p class="text-xs text-slate-400 mt-1">Menempel URL lengkap juga bisa, ID diambil otomatis.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $video->title) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="desc" rows="3" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('desc', $video->desc) }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Perubahan</button>
                <a href="{{ route('admin.videos.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const input = document.getElementById('videoIdInput');
    const frame = document.getElementById('videoFrame');
    let timer;

    function extractId(v) {
        v = v.trim();
        const m = v.match(/(?:v=|youtu\.be\/|embed\/|shorts\/)([A-Za-z0-9_-]{6,20})/);
        if (m) return m[1];
        return /^[A-Za-z0-9_-]{6,20}$/.test(v) ? v : '';
    }
    function update() {
        const id = extractId(input.value);
        if (id && id !== input.value.trim()) input.value = id;
        if (id) {
            const src = 'https://www.youtube-nocookie.com/embed/' + id;
            if (frame.src !== src) frame.src = src;
        }
    }
    input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(update, 400); });
})();
</script>
@endpush
