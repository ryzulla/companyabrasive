@extends('admin.layouts.app')

@section('title', 'Edit Klien')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.clients.update', $client) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Perusahaan / Klien <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $client->name) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Logo Perusahaan</label>
                @if($client->logo)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" class="h-16 w-auto rounded-lg border border-slate-200 object-contain bg-slate-50 p-1">
                        <span class="text-xs text-slate-400">Logo saat ini</span>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" id="logoInput"
                       class="w-full text-sm border border-slate-200 rounded-lg px-3.5 py-2.5 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah logo. Format: JPG, PNG, WebP. Maks 2MB.</p>
                <div id="logoPreview" class="mt-3 hidden">
                    <img id="previewImg" src="" alt="Preview" class="h-16 w-auto rounded-lg border border-slate-200 object-contain bg-slate-50 p-1">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Urutan Tampil</label>
                <input type="number" name="order" value="{{ old('order', $client->order) }}"
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Perubahan</button>
                <a href="{{ route('admin.clients.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('logoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('logoPreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
