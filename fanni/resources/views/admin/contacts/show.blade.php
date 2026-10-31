@extends('admin.layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pesan Masuk
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden max-w-2xl">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-sm font-semibold text-slate-700">Detail Pesan</h3>
        <p class="text-xs text-slate-400 mt-0.5">{{ $contactMessage->created_at->format('d M Y, H:i') }}</p>
    </div>

    <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-3 gap-y-4 text-sm">
            <span class="text-slate-400 font-medium">Nama</span>
            <span class="col-span-2 text-slate-700 font-medium">{{ $contactMessage->name }}</span>

            <span class="text-slate-400 font-medium">Email</span>
            <span class="col-span-2">
                <a href="mailto:{{ $contactMessage->email }}" class="text-blue-600 hover:underline">{{ $contactMessage->email }}</a>
            </span>

            <span class="text-slate-400 font-medium">Keperluan</span>
            <span class="col-span-2 text-slate-700">{{ $contactMessage->subject ?: '-' }}</span>

            <span class="text-slate-400 font-medium">Telepon</span>
            <span class="col-span-2">
                <a href="tel:{{ $contactMessage->phone }}" class="text-blue-600 hover:underline">{{ $contactMessage->phone }}</a>
            </span>
        </div>

        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-400 font-medium mb-2">Pesan</p>
            <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $contactMessage->message }}</div>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
        <a href="{{ route('admin.contacts.index') }}" class="text-sm text-slate-500 hover:text-slate-700 transition">
            Kembali
        </a>
        <form method="POST" action="{{ route('admin.contacts.destroy', $contactMessage) }}" onsubmit="return confirm('Hapus pesan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                Hapus Pesan
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden max-w-2xl mt-5">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-sm font-semibold text-slate-700">Balas Pesan</h3>
        <p class="text-xs text-slate-400 mt-0.5">Kirim langsung lewat email, atau buka WhatsApp Web dengan pesan yang sudah terisi.</p>
    </div>
    @php
        $defaultBody = "Halo {$contactMessage->name},\n\nTerima kasih telah menghubungi kami. \n\nSalam,\n" . ($s['company_name'] ?? config('app.name'));
    @endphp
    <form method="POST" action="{{ route('admin.contacts.reply-email', $contactMessage) }}" id="replyForm" class="px-6 py-5 space-y-4">
        @csrf
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Subjek email</label>
            <input type="text" name="subject" value="{{ old('subject', 'Re: Permintaan Anda di ' . ($s['company_name'] ?? config('app.name'))) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Isi balasan</label>
            <textarea name="body" id="replyBody" rows="6" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y">{{ old('body', $defaultBody) }}</textarea>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                Kirim Email ke {{ $contactMessage->email }}
            </button>
            <button type="button" onclick="replyWhatsApp()" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                Balas via WhatsApp Web
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function replyWhatsApp() {
    // Normalisasi nomor ke format internasional Indonesia: 08xx / +62 8xx -> 628xx
    let phone = @json($contactMessage->phone).replace(/\D/g, '');
    if (phone.startsWith('0')) phone = '62' + phone.slice(1);
    else if (phone.startsWith('8')) phone = '62' + phone;
    const text = document.getElementById('replyBody').value;
    window.open('https://web.whatsapp.com/send?phone=' + phone + '&text=' + encodeURIComponent(text), '_blank');
}
</script>
@endpush
