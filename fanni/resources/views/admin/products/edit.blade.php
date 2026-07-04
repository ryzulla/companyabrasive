@extends('admin.layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="bg-white rounded-xl border border-slate-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Meta / Subtitle <span class="text-red-500">*</span></label>
                    <input type="text" name="meta" value="{{ old('meta', $product->meta) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Badge <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Material <span class="text-red-500">*</span></label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Label Spesifikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="spec_label" value="{{ old('spec_label', $product->spec_label) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Spesifikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="spec_val" value="{{ old('spec_val', $product->spec_val) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">RPM <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="rpm" value="{{ old('rpm', $product->rpm) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="desc" rows="4" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('desc', $product->desc) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Gambar</label>
                <div class="relative">
                    <input type="file" name="img" accept="image/*" id="imgInput" class="sr-only">
                    <label for="imgInput" id="imgLabel" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition overflow-hidden" style="min-height:160px;">
                        @if($product->img)
                            <div id="imgEmpty" class="hidden"></div>
                            <img id="imgPreview" src="{{ Storage::url($product->img) }}" alt="Preview" class="w-full h-40 object-cover">
                        @else
                            <div id="imgEmpty" class="flex flex-col items-center gap-2 text-slate-400 py-8">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm">Klik untuk pilih gambar</span>
                                <span class="text-xs">JPG, PNG, WebP · Maks 2MB</span>
                            </div>
                            <img id="imgPreview" src="" alt="Preview" class="w-full h-40 object-cover hidden">
                        @endif
                    </label>
                    <button type="button" id="imgChange" class="absolute bottom-2 right-2 {{ $product->img ? '' : 'hidden' }} bg-white text-xs text-slate-600 px-2.5 py-1 rounded-md border border-slate-200 hover:bg-slate-50 transition shadow-sm" onclick="document.getElementById('imgInput').click()">Ganti</button>
                </div>
                <p class="text-xs text-slate-400 mt-1.5">Kosongkan jika tidak ingin mengubah gambar.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Perubahan</button>
                <a href="{{ route('admin.products.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imgInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('imgPreview').src = e.target.result;
        document.getElementById('imgPreview').classList.remove('hidden');
        document.getElementById('imgEmpty').classList.add('hidden');
        document.getElementById('imgChange').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
