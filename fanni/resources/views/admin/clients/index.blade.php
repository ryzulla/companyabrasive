@extends('admin.layouts.app')

@section('title', 'Klien / Mitra')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $clients->total() }} klien
        @if(request('q'))<span class="ml-1 text-slate-400">— hasil "<strong class="text-slate-600">{{ request('q') }}</strong>"</span>@endif
    </p>
    <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Klien
    </a>
</div>

<form method="GET" action="{{ route('admin.clients.index') }}" class="mb-6 flex gap-2">
    <div class="flex items-center gap-2 flex-1 max-w-sm bg-white border border-slate-300 rounded-lg px-3 transition">
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama klien..."
               class="w-full text-sm py-2.5 text-slate-700 placeholder:text-slate-400 focus:outline-none bg-transparent">
    </div>
    <button type="submit" class="text-sm text-white px-4 py-2.5 rounded-lg font-medium shrink-0" style="background:#4f46e5;">Cari</button>
    @if(request('q'))
        <a href="{{ route('admin.clients.index') }}" class="text-sm border border-slate-200 hover:bg-slate-50 text-slate-500 px-3 py-2.5 rounded-lg transition shrink-0">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($clients->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <p class="text-sm">{{ request('q') ? 'Tidak ada klien yang cocok.' : 'Belum ada klien/mitra terdaftar' }}</p>
        </div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[480px] admin-table">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Logo</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Nama</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Urutan</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($clients as $client)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3 cell-media">
                            @if($client->logo)
                                <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" class="h-10 w-auto max-w-[100px] object-contain rounded bg-slate-50 border border-slate-100 p-1">
                            @else
                                <div class="h-10 w-16 bg-slate-100 rounded flex items-center justify-center text-slate-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3 font-medium text-slate-700" data-label="Nama">{{ $client->name }}</td>
                        <td class="px-6 py-3 text-slate-500" data-label="Urutan">{{ $client->order }}</td>
                        <td class="px-6 py-3 text-right cell-actions">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.clients.edit', $client) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Hapus klien ini?')">
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
        @if($clients->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $clients->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
