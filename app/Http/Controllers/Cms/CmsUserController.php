<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class CmsUserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('cms.user.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('create', User::class);

        return view('cms.user.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Defensive check: Only Root Super Admin can assign 'super_admin' role
        if ($request->role === 'super_admin' && !$request->user()->isRootSuperAdmin()) {
            abort(403, 'Hanya Root Super Admin yang berhak membuat akun Super Admin.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_root_super_admin' => false,
            'is_active' => $request->is_active,
        ]);

        ActivityLog::record('create_user', 'Manajemen User', "Menambahkan user baru: {$user->name} ({$user->role})");

        return redirect()->route('cms.user.index')->with('success', 'User pengelola berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        Gate::authorize('update', $user);

        return view('cms.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Gate::authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'role' => 'required|in:super_admin,admin',
            'is_active' => 'required|boolean',
        ]);

        // Defensive check: Only Root Super Admin can assign 'super_admin' role
        if ($request->role === 'super_admin' && !$request->user()->isRootSuperAdmin()) {
            abort(403, 'Hanya Root Super Admin yang berhak mengubah role menjadi Super Admin.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Prevent self-deactivation
        if ($user->id === Auth::id() && !$request->is_active) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = $request->is_active;
        $user->save();

        ActivityLog::record('update_user', 'Manajemen User', "Mengubah data user: {$user->name}");

        return redirect()->route('cms.user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        Gate::authorize('delete', $user);

        $name = $user->name;
        $user->delete();

        ActivityLog::record('delete_user', 'Manajemen User', "Menghapus user: {$name}");

        return redirect()->route('cms.user.index')->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Gate::authorize('resetPassword', $user);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::record('reset_user_password', 'Manajemen User', "Reset password untuk user: {$user->name}");

        return back()->with('success', "Password untuk user '{$user->name}' berhasil diperbarui.");
    }
}
