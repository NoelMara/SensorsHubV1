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
            'student' => User::where('role', 'student')->count(),
            'instructor'       => User::where('role', 'instructor')->count(),
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
            'role'     => ['required', Rule::in(['student', 'instructor'])], 
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
       // Prevent editing another Administrator
        if ($user->isAdministrator() && !$user->is(auth()->user())) {
            return back()->with('error', 'You cannot edit another Administrator account.');
        }

        return view('super-admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdministrator() && !$user->is(auth()->user())) {
            return back()->with('error', 'You cannot edit another Administrator account.');
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'  => ['nullable', Rule::in(['student', 'instructor'])], 
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update role if provided (not for Administrators)
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
            'role' => ['required', Rule::in(['student', 'instructor', 'super_admin'])],
        ]);

        // Protect the original Administrator (env account) from being demoted
        $originalFaculty = User::where('role', 'super_admin')->oldest()->first();
        if ($user->is($originalFaculty) && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'The original Administrator account cannot be demoted.');
        }

        if ($user->is(auth()->user()) && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'You cannot remove your own Administrator access.');
        }

        $user->update(['role' => $validated['role']]);
        ActivityLogHelper::log('changed', 'user', "changed {$user->name}'s role to " . ($validated['role'] === 'instructor' ? 'Instructor' : ($validated['role'] === 'super_admin' ? 'Administrator' : 'Student')));

        return back()->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isAdministrator()) {
            return back()->with('error', 'Administrator accounts cannot be removed from this screen.');
        }

        $user->delete();
        ActivityLogHelper::log('deleted', 'user', "deleted account '{$user->name}'");

        return back()->with('success', 'Account removed successfully.');
    }
}