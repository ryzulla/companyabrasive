@extends('admin.layouts.app')

@section('title', 'Tambah Produk')

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

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama produk">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Merek · Jenis <span class="text-red-500">*</span></label>
                    <input type="text" name="meta" value="{{ old('meta') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: TAIYO · Waterproof">
                    <p class="text-xs text-slate-400 mt-1">Format: Merek · Jenis produk, tampil di kartu dan halaman detail.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Badge <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="badge" value="{{ old('badge') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Baru, Terlaris">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Material / Aplikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="material" value="{{ old('material') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Logam, kayu, cat">
                    <p class="text-xs text-slate-400 mt-1">Bahan atau tempat produk dipakai, mis. "Logam, kayu, cat" atau "Tembok, pagar".</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Label Spesifikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="spec_label" value="{{ old('spec_label') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Grit" list="specLabels" autocomplete="off">
                    <p class="text-xs text-slate-400 mt-1">Pilih dari yang sudah ada agar konsisten, atau ketik label baru.</p>
                    <datalist id="specLabels">@foreach(\App\Models\Product::query()->distinct()->orderBy('spec_label')->pluck('spec_label') as $lbl)<option value="{{ $lbl }}">@endforeach</datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Spesifikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="spec_val" value="{{ old('spec_val') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: P400 atau 4 inch">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kecepatan Maks (RPM) <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="rpm" value="{{ old('rpm') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: 15.300">
                    <p class="text-xs text-slate-400 mt-1">Isi hanya untuk produk berputar (batu gerinda, flap disc, dll). Kosongkan untuk produk lain.</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="desc" rows="4" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Deskripsi lengkap produk">{{ old('desc') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Foto Produk <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(bisa lebih dari satu, maks 10)</span></label>
                <input type="file" name="images[]" accept="image/*" id="imagesInput" class="sr-only" multiple required>
                <label for="imagesInput" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition py-6 text-slate-400">
                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm">Klik untuk pilih foto</span>
                    <span class="text-xs">JPG, PNG, WebP · Maks 2MB per foto</span>
                </label>
                <div id="imagesPreview" class="gap-3 mt-3" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,130px));"></div>
                <p class="text-xs text-slate-400 mt-1.5">Foto pertama menjadi cover di daftar produk.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan</button>
                <a href="{{ route('admin.products.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const imagesInput = document.getElementById('imagesInput');
const imagesPreview = document.getElementById('imagesPreview');
// FileList tidak bisa ditambah langsung, jadi kumpulkan lewat DataTransfer agar pilihan berikutnya menambah, bukan mengganti.
const picked = new DataTransfer();
imagesInput.addEventListener('change', function () {
    for (const f of this.files) picked.items.add(f);
    this.files = picked.files;
    renderPreview();
});
function removePicked(i) {
    picked.items.remove(i);
    imagesInput.files = picked.files;
    renderPreview();
}
function renderPreview() {
    imagesPreview.innerHTML = '';
    Array.from(picked.files).forEach((f, i) => {
        const url = URL.createObjectURL(f);
        const el = document.createElement('div');
        el.className = 'relative rounded-lg overflow-hidden border border-slate-200';
        el.innerHTML = `<img src="${url}" class="w-full object-cover" style="aspect-ratio:1/1; display:block;" alt="">
            <button type="button" onclick="removePicked(${i})" class="absolute top-1 right-1 bg-white/90 text-red-600 rounded-full w-5 h-5 text-xs leading-5">&times;</button>`;
        imagesPreview.appendChild(el);
    });
}
</script>
@endpush
