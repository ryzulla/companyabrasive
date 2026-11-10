@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-500">{{ $users->count() }} pengguna</p>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Pengguna
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto admin-table-wrap">
    <table class="w-full text-sm min-w-[560px] admin-table js-datatable">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-slate-600">Nama</th>
                <th class="text-left px-6 py-3 font-medium text-slate-600">Email</th>
                <th class="text-left px-6 py-3 font-medium text-slate-600">Role</th>
                <th class="text-left px-6 py-3 font-medium text-slate-600">Dibuat</th>
                <th class="text-right px-6 py-3 font-medium text-slate-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($users as $user)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3" data-label="Nama">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div class="font-medium text-slate-700">
                                {{ $user->name }}
                                @if($user->is(auth()->user()))<span class="ml-1.5 bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-semibold align-middle">Anda</span>@endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-slate-600" data-label="Email">{{ $user->email }}</td>
                    <td class="px-6 py-3" data-label="Role">
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $user->isSuperuser() ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $user->roleLabel() }}</span>
                    </td>
                    <td class="px-6 py-3 text-slate-500 whitespace-nowrap" data-label="Dibuat" data-order="{{ $user->created_at?->timestamp }}">{{ $user->created_at?->format('d M Y') }}</td>
                    <td class="px-6 py-3 text-right cell-actions">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</a>
                            @unless($user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">Hapus</button>
                                </form>
                            @endunless
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
