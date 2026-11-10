@extends('admin.layouts.app')

@section('title', 'Testimoni')

@section('content')
@php
    $pending = $testimonials->where('status', 'pending')->count();
    $newInvite = session('new_invite') ? $invites->firstWhere('id', session('new_invite')) : null;
    $company = \App\Models\Setting::get('company_name', 'kami');
@endphp

{{-- LINK UNDANGAN PRIVAT --}}
<div class="bg-white rounded-xl border border-slate-100 p-5 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h3 class="text-sm font-semibold text-slate-800">Link Testimoni untuk Klien</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xl">Buat link khusus untuk satu klien. Link hanya bisa dipakai sekali, bisa diberi masa berlaku, dan testimoni yang masuk menunggu persetujuan Anda sebelum tampil di website.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.invites.store') }}" class="flex flex-wrap items-end gap-3 mt-4">
        @csrf
        <div class="flex-1 min-w-[220px]">
            <label class="block text-xs font-medium text-slate-600 mb-1">Untuk siapa? <span class="text-slate-400 font-normal">(catatan internal)</span></label>
            <input type="text" name="label" required maxlength="150" value="{{ old('label') }}" placeholder="Contoh: PT Maju Jaya - Pak Budi" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('label')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Masa berlaku</label>
            <select name="days" class="px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="7">7 hari</option><option value="14">14 hari</option><option value="30" selected>30 hari</option><option value="90">90 hari</option><option value="">Tanpa batas</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">Buat Link</button>
    </form>

    @if($newInvite)
        @php $wa = 'https://wa.me/?text=' . urlencode("Halo, terima kasih sudah bekerja sama dengan {$company}. Boleh minta waktu sebentar untuk mengisi testimoni singkat lewat link ini: " . $newInvite->url()); @endphp
        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3.5">
            <p class="text-xs font-semibold text-emerald-800 mb-2">Link untuk {{ $newInvite->label }}</p>
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" readonly value="{{ $newInvite->url() }}" onclick="this.select()" class="flex-1 min-w-[240px] px-3 py-2 bg-white border border-emerald-200 rounded-md text-xs font-mono text-slate-700">
                <button type="button" onclick="copyInvite(this, '{{ $newInvite->url() }}')" class="px-3 py-2 bg-white border border-emerald-300 text-emerald-700 text-xs font-medium rounded-md hover:bg-emerald-100 transition">Salin</button>
                <a href="{{ $wa }}" target="_blank" rel="noopener" class="px-3 py-2 bg-emerald-600 text-white text-xs font-medium rounded-md hover:bg-emerald-700 transition">Kirim via WhatsApp</a>
            </div>
        </div>
    @endif

    @if($invites->isNotEmpty())
        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-xs min-w-[520px]">
                <thead><tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-2 pr-3 font-medium">Untuk</th><th class="py-2 pr-3 font-medium">Status</th><th class="py-2 pr-3 font-medium">Berlaku s/d</th><th class="py-2 text-right font-medium">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($invites as $invite)
                    <tr>
                        <td class="py-2.5 pr-3 text-slate-700">{{ $invite->label }}</td>
                        <td class="py-2.5 pr-3">
                            <span class="inline-block rounded-full px-2 py-0.5 font-medium {{ $invite->isUsed() ? 'bg-slate-100 text-slate-500' : ($invite->isExpired() ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">{{ $invite->statusLabel() }}</span>
                        </td>
                        <td class="py-2.5 pr-3 text-slate-500">{{ $invite->expires_at ? $invite->expires_at->translatedFormat('d M Y') : 'Tanpa batas' }}</td>
                        <td class="py-2.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($invite->isUsable())
                                    <button type="button" onclick="copyInvite(this, '{{ $invite->url() }}')" class="text-blue-600 hover:text-blue-800 font-medium">Salin link</button>
                                @endif
                                <form method="POST" action="{{ route('admin.testimonials.invites.destroy', $invite) }}" onsubmit="return confirm('{{ $invite->isUsable() ? 'Cabut link ini? Klien tidak bisa lagi memakainya.' : 'Hapus catatan link ini?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">{{ $invite->isUsable() ? 'Cabut' : 'Hapus' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($pending)
    <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 text-sm px-4 py-3">
        <strong>{{ $pending }} testimoni baru</strong> menunggu persetujuan. Klik <em>Setujui</em> agar tampil di website, atau <em>Hapus</em> untuk menolak.
    </div>
@endif

<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">{{ $testimonials->count() }} testimoni</p>
    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Testimoni
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($testimonials->isEmpty())
        <div class="text-center py-16 text-slate-400"><p class="text-sm">Belum ada testimoni</p></div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[560px] admin-table js-datatable">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Nama</th><th class="text-left px-6 py-3 font-medium text-slate-600">Testimoni</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($testimonials as $testimonial)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 " data-label="Nama"><div class="font-medium text-slate-700">{{ $testimonial->author }}@if($testimonial->isPending()) <span class="ml-1.5 inline-block align-middle rounded-full bg-amber-100 text-amber-700 text-[10px] font-semibold px-2 py-0.5">Menunggu</span>@endif</div><div class="text-slate-400 text-xs">{{ $testimonial->pos }}</div></td><td class="px-6 py-4 max-w-md" data-label="Testimoni"><p class="line-clamp-2 text-slate-500 italic">"{{ $testimonial->text }}"</p></td>
                        <td class="px-6 py-4 text-right cell-actions">
                            <div class="flex items-center justify-end gap-3">
                                @if($testimonial->isPending())
                                    <form method="POST" action="{{ route('admin.testimonials.approve', $testimonial) }}">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-xs font-semibold transition">Setujui</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
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
    @endif
</div>
@endsection

@push('scripts')
<script>
function copyInvite(btn, url) {
    var done = function () { var t = btn.textContent; btn.textContent = 'Tersalin!'; setTimeout(function () { btn.textContent = t; }, 1600); };
    if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(url).then(done); return; }
    var i = document.createElement('input'); i.value = url; document.body.appendChild(i); i.select();
    try { document.execCommand('copy'); done(); } catch (e) { window.prompt('Salin link ini:', url); }
    document.body.removeChild(i);
}
</script>
@endpush
