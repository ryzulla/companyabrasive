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
@endsection
