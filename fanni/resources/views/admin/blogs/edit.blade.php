@extends('admin.layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="space-y-5" id="blogForm">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $blog->title) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Meta / Subtitle <span class="text-red-500">*</span></label>
                <input type="text" name="meta" value="{{ old('meta', $blog->meta) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Konten <span class="text-red-500">*</span></label>
                {{-- Hidden textarea — diisi oleh CKEditor sebelum submit --}}
                <textarea name="desc" id="descInput" class="hidden">{{ old('desc', $blog->desc) }}</textarea>
                {{-- CKEditor container — initial content dari data blog --}}
                <div id="editor" class="border border-slate-200 rounded-lg overflow-hidden">{!! old('desc', $blog->desc) !!}</div>
                @error('desc')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Gambar Cover</label>
                <div class="relative">
                    <input type="file" name="img" accept="image/*" id="imgInput" class="sr-only">
                    <label for="imgInput" id="imgLabel" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition overflow-hidden" style="min-height:160px;">
                        @if($blog->img)
                            <div id="imgEmpty" class="hidden"></div>
                            <img id="imgPreview" src="{{ Storage::url($blog->img) }}" alt="Preview" class="w-full h-40 object-cover">
                        @else
                            <div id="imgEmpty" class="flex flex-col items-center gap-2 text-slate-400 py-8">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm">Klik untuk pilih gambar</span>
                                <span class="text-xs">JPG, PNG, WebP · Maks 2MB</span>
                            </div>
                            <img id="imgPreview" src="" alt="Preview" class="w-full h-40 object-cover hidden">
                        @endif
                    </label>
                    <button type="button" id="imgChange" class="absolute bottom-2 right-2 {{ $blog->img ? '' : 'hidden' }} bg-white text-xs text-slate-600 px-2.5 py-1 rounded-md border border-slate-200 hover:bg-slate-50 transition shadow-sm" onclick="document.getElementById('imgInput').click()">Ganti</button>
                </div>
                <p class="text-xs text-slate-400 mt-1.5">Kosongkan jika tidak ingin mengubah gambar.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Perubahan</button>
                <a href="{{ route('admin.blogs.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
let ckEditor;

ClassicEditor.create(document.querySelector('#editor'), {
    toolbar: {
        items: [
            'heading', '|',
            'bold', 'italic', 'underline', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', 'horizontalLine', '|',
            'undo', 'redo'
        ]
    },
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
        ]
    },
    language: 'id',
}).then(editor => {
    ckEditor = editor;

    editor.editing.view.change(writer => {
        writer.setStyle('min-height', '280px', editor.editing.view.document.getRoot());
    });
}).catch(err => console.error(err));

// Sync CKEditor → textarea sebelum submit
document.getElementById('blogForm').addEventListener('submit', function(e) {
    if (ckEditor) {
        const content = ckEditor.getData();
        if (!content.trim()) {
            e.preventDefault();
            alert('Konten artikel tidak boleh kosong.');
            return;
        }
        document.getElementById('descInput').value = content;
    }
});

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

<style>
.ck-editor__editable { min-height: 280px !important; font-size: 0.9rem; line-height: 1.7; }
.ck.ck-toolbar { border-color: #e2e8f0 !important; background: #f8fafc !important; }
.ck.ck-editor__main .ck-editor__editable { border-color: #e2e8f0 !important; }
.ck.ck-editor__main .ck-editor__editable:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important; }
</style>
@endpush
