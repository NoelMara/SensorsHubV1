<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailWithCode;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');

        $users = User::when($role, fn ($query) => $query->where('role', $role))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $roleCounts = [
            'all'         => User::count(),
            'user'        => User::where('role', 'user')->count(),
            'admin'       => User::where('role', 'admin')->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
        ];

        return view('super-admin.users.index', compact('users', 'roleCounts', 'role'));
    }

    public function create()
    {
        return view('super-admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'     => ['required', Rule::in(['user', 'admin'])], // super_admin not allowed here
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'role'              => $validated['role'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(), // admin created accounts are pre-verified
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Account created successfully.');
    }

    public function show(User $user)
    {
        return view('super-admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
       // Prevent editing another Faculty Head
        if ($user->isSuperAdmin() && !$user->is(auth()->user())) {
            return back()->with('error', 'You cannot edit another Faculty Head account.');
        }

        return view('super-admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !$user->is(auth()->user())) {
            return back()->with('error', 'You cannot edit another Faculty Head account.');
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'  => ['nullable', Rule::in(['user', 'admin'])], // ← change 'required' to 'nullable'
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update role if provided (not for Faculty Heads)
        if (isset($validated['role'])) {
            $updateData['role'] = $validated['role'];
        }

        if ($validated['password']) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Account updated successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'You cannot remove your own Faculty Head access.');
        }

        $user->update(['role' => $validated['role']]);
        ActivityLogHelper::log('changed', 'user', "changed {$user->name}'s role to " . ($validated['role'] === 'admin' ? 'Instructor' : ($validated['role'] === 'super_admin' ? 'Faculty Head' : 'Student')));

        return back()->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Faculty Head accounts cannot be removed from this screen.');
        }

        $user->delete();
        ActivityLogHelper::log('deleted', 'user', "deleted account '{$user->name}'");

        return back()->with('success', 'Account removed successfully.');
    }
}