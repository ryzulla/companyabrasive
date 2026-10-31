@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5" autocomplete="off">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" @disabled($user->is(auth()->user()))>
                    @foreach(\App\Models\User::ROLES as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($user->is(auth()->user()))<input type="hidden" name="role" value="{{ $user->role }}"><p class="text-xs text-amber-600 mt-1.5">Role akun Anda sendiri tidak dapat diubah.</p>@endif
                <p class="text-xs text-slate-400 mt-1.5"><strong>Superuser</strong>: akses penuh termasuk Pengaturan, Pengguna, Kategori, dan Klien. <strong>Admin</strong>: mengelola Produk, Blog, Video, Testimoni, FAQ, dan Pesan Masuk.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="password" name="password"  minlength="8" autocomplete="new-password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ulangi Password</label>
                    <input type="password" name="password_confirmation"  minlength="8" autocomplete="new-password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <p class="text-xs text-slate-400 -mt-2">Minimal 8 karakter. Kosongkan jika tidak ingin mengganti password.</p>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
