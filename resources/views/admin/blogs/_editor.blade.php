{{-- Editor konten artikel (CKEditor 5): teks, gambar (upload / tempel / URL), dan video (YouTube, Vimeo) --}}
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
let ckEditor;
const UPLOAD_URL = @json(route('admin.blogs.upload-image'));
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || @json(csrf_token());

// Upload gambar (pilih file, tempel dari clipboard, atau seret-lepas) → disimpan di storage, editor menerima URL-nya
class UploadAdapter {
    constructor(loader) { this.loader = loader; }
    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            const body = new FormData();
            body.append('upload', file);
            fetch(UPLOAD_URL, { method: 'POST', body, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, credentials: 'same-origin' })
                .then(async r => {
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok) throw new Error(data.message || ('Upload gagal (' + r.status + ')'));
                    resolve({ default: data.url });
                })
                .catch(err => reject(err.message));
        }));
    }
    abort() {}
}
function UploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = loader => new UploadAdapter(loader);
}

// Tombol "Gambar dari URL"
function ImageUrlPlugin(editor) {
    editor.ui.componentFactory.add('imageUrl', locale => {
        // Build CDN tidak mengekspos ButtonView; ambil kelasnya dari tombol bawaan
        const ButtonView = editor.ui.componentFactory.create('bold').constructor;
        const view = new ButtonView(locale);
        view.set({
            label: 'Gambar dari URL',
            tooltip: true,
            withText: false,
            icon: '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.1-1.05h14.38zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z"/></svg>'
        });
        view.on('execute', () => {
            const url = (window.prompt('Tempel link (URL) gambar:') || '').trim();
            if (!url) return;
            if (!/^https?:\/\//i.test(url)) { alert('URL gambar harus diawali http:// atau https://'); return; }
            editor.execute('insertImage', { source: url });
            editor.editing.view.focus();
        });
        return view;
    });
}

ClassicEditor.create(document.querySelector('#editor'), {
    extraPlugins: [UploadAdapterPlugin, ImageUrlPlugin],
    toolbar: {
        items: [
            'heading', '|',
            'bold', 'italic', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', '|',
            'uploadImage', 'imageUrl', 'mediaEmbed', '|',
            'undo', 'redo'
        ],
        shouldNotGroupWhenFull: true
    },
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
        ]
    },
    image: {
        toolbar: ['imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|', 'toggleImageCaption', 'imageTextAlternative'],
    },
    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
    // Video disimpan sebagai <oembed url="..."> lalu diubah menjadi iframe saat ditampilkan di website
    mediaEmbed: { previewsInData: false },
    language: 'id',
}).then(editor => {
    ckEditor = editor;
}).catch(err => console.error(err));

// Sync CKEditor → textarea sebelum submit
document.getElementById('blogForm').addEventListener('submit', function (e) {
    if (!ckEditor) return;
    const content = ckEditor.getData();
    if (!content.trim()) {
        e.preventDefault();
        alert('Konten artikel tidak boleh kosong.');
        return;
    }
    document.getElementById('descInput').value = content;
});
</script>

<style>
.ck-editor__editable { min-height: 320px !important; font-size: 0.92rem; line-height: 1.75; }
.ck.ck-toolbar { border-color: #e2e8f0 !important; background: #f8fafc !important; }
.ck.ck-editor__main .ck-editor__editable { border-color: #e2e8f0 !important; }
.ck.ck-editor__main .ck-editor__editable:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,0.12) !important; }
.ck-content img { max-width: 100%; height: auto; }
.ck-content figure.media { margin: 1em 0; }
</style>
@endpush
