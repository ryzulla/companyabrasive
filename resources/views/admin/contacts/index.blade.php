@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $messages->count() }} pesan masuk
    </p>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($messages->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-sm">Belum ada pesan masuk</p>
        </div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[560px] admin-table js-datatable">
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
                            @if($message->subject)<span class="inline-block text-xs bg-blue-50 text-blue-600 rounded px-2 py-0.5 mb-1">{{ $message->subject }}</span>@endif
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
    @endif
</div>
@endsection
