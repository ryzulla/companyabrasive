@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $messages->total() }} pesan masuk
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
</div>

<form method="GET" action="{{ route('admin.contacts.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau pesan..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.contacts.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($messages->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-sm">Belum ada pesan masuk</p>
        </div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[560px] admin-table">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Pengirim</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Pesan</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Tanggal</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($messages as $message)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4" data-label="Pengirim">
                            <div class="font-medium text-slate-700">{{ $message->name }}</div>
                            <div class="text-slate-400 text-xs">{{ $message->email }}</div>
                            <div class="text-slate-400 text-xs">{{ $message->phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-sm" data-label="Pesan">
                            <p class="line-clamp-2">{{ $message->message }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap" data-label="Tanggal">{{ $message->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-right cell-actions">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.contacts.show', $message) }}"
                                   class="text-blue-500 hover:text-blue-700 text-xs font-medium transition">Lihat</a>
                                <form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">
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
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
