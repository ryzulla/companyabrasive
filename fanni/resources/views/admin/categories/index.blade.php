@extends('admin.layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">
        {{ $categories->count() }} kategori
    </p>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    @if($categories->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm">{{ request('q') ? 'Tidak ada kategori yang cocok.' : 'Belum ada kategori' }}</p>
        </div>
    @else
        <div class="overflow-x-auto admin-table-wrap">
        <table class="w-full text-sm min-w-[560px] admin-table js-datatable">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Gambar</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">ID</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Judul</th>
                    <th class="text-left px-6 py-3 font-medium text-slate-600">Produk</th>
                    <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($categories as $category)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3 cell-media">
                            @if($category->img)
                                <img src="{{ Storage::url($category->img) }}" class="w-12 h-12 object-cover rounded-lg" alt="">
                            @else
                                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3" data-label="ID">
                            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-mono">{{ $category->id }}</span>
                        </td>
                        <td class="px-6 py-3 font-medium text-slate-700" data-label="Judul">{{ $category->title }}</td>
                        <td class="px-6 py-3 text-slate-500" data-label="Produk">{{ $category->products_count }}</td>
                        <td class="px-6 py-3 text-right cell-actions">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
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
