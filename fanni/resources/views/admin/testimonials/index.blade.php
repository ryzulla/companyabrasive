@extends('admin.layouts.app')

@section('title', 'Testimoni')

@section('content')
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
                        <td class="px-6 py-4 " data-label="Nama"><div class="font-medium text-slate-700">{{ $testimonial->author }}</div><div class="text-slate-400 text-xs">{{ $testimonial->pos }}</div></td><td class="px-6 py-4 max-w-md" data-label="Testimoni"><p class="line-clamp-2 text-slate-500 italic">"{{ $testimonial->text }}"</p></td>
                        <td class="px-6 py-4 text-right cell-actions">
                            <div class="flex items-center justify-end gap-3">
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
