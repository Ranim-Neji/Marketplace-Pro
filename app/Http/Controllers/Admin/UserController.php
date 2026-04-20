<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::all();

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load('orders', 'products', 'roles');
        return view('pages.admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'      => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user->syncRoles([$request->role]);
        $user->update(['is_active' => $request->boolean('is_active', true)]);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 403, 'Cannot delete admin accounts.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
