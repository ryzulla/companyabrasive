<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        // Cegah terkunci: role akun sendiri tidak boleh diubah, dan superuser terakhir tidak boleh diturunkan.
        if ($data['role'] !== $user->role) {
            if ($user->is($request->user())) {
                return back()->withInput()->withErrors(['role' => 'Anda tidak dapat mengubah role akun Anda sendiri.']);
            }
            if ($user->isSuperuser() && User::where('role', User::ROLE_SUPERUSER)->count() <= 1) {
                return back()->withInput()->withErrors(['role' => 'Superuser terakhir tidak dapat diturunkan rolenya.']);
            }
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang dipakai. Gunakan akun lain untuk menghapusnya.');
        }
        if ($user->isSuperuser() && User::where('role', User::ROLE_SUPERUSER)->count() <= 1) {
            return back()->with('error', 'Superuser terakhir tidak dapat dihapus.');
        }
        if (User::count() <= 1) {
            return back()->with('error', 'Pengguna terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
