@extends('admin.layouts.app')

@section('title', 'Tambah Video')

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
        <form method="POST" action="{{ route('admin.videos.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Preview Video</label>
                <div id="videoPreview" style="position:relative; width:100%; max-width:720px; aspect-ratio:16/9; background:#0f172a; border-radius:12px; overflow:hidden;">
                    <div id="videoPlaceholder" style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; color:#94a3b8; font-size:14px; text-align:center; padding:16px;">
                        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:.6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span id="videoPlaceholderText">Isi YouTube Video ID di bawah untuk melihat preview.</span>
                    </div>
                    <iframe id="videoFrame" title="Preview video" style="position:absolute; inset:0; width:100%; height:100%; border:0; display:none;" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">YouTube Video ID <span class="text-red-500">*</span></label>
                <input type="text" name="id" id="videoIdInput" value="{{ old('id') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono" placeholder="contoh: dQw4w9WgXcQ">
                <p class="text-xs text-slate-400 mt-1">ID YouTube adalah bagian setelah <code class="bg-slate-100 px-1 rounded">?v=</code> pada URL video. Menempel URL lengkap juga bisa, ID diambil otomatis.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Judul video">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="desc" rows="3" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Deskripsi singkat video">{{ old('desc') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
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
    const holder = document.getElementById('videoPlaceholder');
    const text = document.getElementById('videoPlaceholderText');
    let timer;

    // Terima ID polos atau URL (watch?v=, youtu.be/, embed/, shorts/)
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
            frame.style.display = 'block';
            holder.style.display = 'none';
        } else {
            frame.removeAttribute('src');
            frame.style.display = 'none';
            holder.style.display = 'flex';
            text.textContent = input.value.trim()
                ? 'ID atau URL YouTube tidak dikenali.'
                : 'Isi YouTube Video ID di bawah untuk melihat preview.';
        }
    }
    input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(update, 400); });
    update();
})();
</script>
@endpush
