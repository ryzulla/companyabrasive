@extends('admin.layouts.app')

@section('title', 'Akun Saya')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 items-start">
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100">
            <div class="w-14 h-14 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h3 class="font-semibold text-slate-800">Profil</h3>
                <p class="text-xs mb-0.5"><span class="px-2 py-0.5 rounded font-medium {{ $user->isSuperuser() ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $user->roleLabel() }}</span></p>
                <p class="text-xs text-slate-400">Bergabung sejak {{ $user->created_at?->format('d M Y') }}</p>
            </div>
        </div>
        @if($errors->default->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->default->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.account.update') }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Simpan Profil</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-100 p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Ganti Password</h3>
        <p class="text-xs text-slate-400 mb-5 pb-5 border-b border-slate-100">Gunakan password yang kuat dan tidak dipakai di layanan lain.</p>
        @if($errors->password->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->password->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.account.password') }}" class="space-y-5" autocomplete="off">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required autocomplete="current-password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8" autocomplete="new-password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ulangi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <p class="text-xs text-slate-400 -mt-2">Minimal 8 karakter.</p>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Ganti Password</button>
        </form>
    </div>
</div>
@endsection
