<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // getDataUser() : User (list semua user)
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // tambahUser() : void (form)
    public function create()
    {
        return view('admin.users.create');
    }

    // tambahUser() : void (simpan)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:admin,kasir',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // ubahUser() : void (form edit)
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // ubahUser() : void (update)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,kasir',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // hapusUser() : void
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
