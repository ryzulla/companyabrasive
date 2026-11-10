@extends('admin.layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">{{ $faqs->count() }} FAQ</p>
    <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah FAQ
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($faqs->isEmpty())
        <div class="text-center py-16 text-slate-400"><p class="text-sm">Belum ada FAQ</p></div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[560px] admin-table js-datatable">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Pertanyaan</th><th class="text-left px-6 py-3 font-medium text-slate-600">Jawaban</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($faqs as $faq)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 max-w-xs" data-label="Pertanyaan"><span class="font-medium text-slate-700">{{ $faq->question }}</span></td><td class="px-6 py-4 max-w-md" data-label="Jawaban"><p class="line-clamp-2 text-slate-500">{{ $faq->answer }}</p></td>
                        <td class="px-6 py-4 text-right cell-actions">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">
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
