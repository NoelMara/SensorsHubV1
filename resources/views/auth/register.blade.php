{{-- Main layout --}}
@extends('layouts.app')

{{-- Page title --}}
@section('title', 'Register')

@push('styles')
<style>
    /* Light mode background */
    .auth-shell {
        background:
            radial-gradient(circle at 18% 14%, rgba(16, 185, 129, 0.12), transparent 28%),
            radial-gradient(circle at 84% 18%, rgba(59, 130, 246, 0.15), transparent 30%),
            linear-gradient(135deg, #f8fafc 0%, #edf7f2 52%, #f7fafc 100%);
    }

    /* Dark mode background */
    .dark .auth-shell {
        background:
            radial-gradient(circle at 18% 14%, rgba(16, 185, 129, 0.12), transparent 28%),
            radial-gradient(circle at 84% 18%, rgba(59, 130, 246, 0.12), transparent 30%),
            linear-gradient(135deg, #0f172a 0%, #111827 52%, #0b1120 100%);
    }

    /* Glass effect panel */
    .auth-panel {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(148, 163, 184, 0.28);
        box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12);
        backdrop-filter: blur(18px);
    }

    .dark .auth-panel {
        background: rgba(15, 23, 42, 0.88);
        border-color: rgba(148, 163, 184, 0.18);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.38);
    }

    /* Input field styling */
    .auth-input {
        transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
    }

    .auth-input:focus {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14);
    }

    /* Feature card row */
    .feature-row {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
</style>
@endpush

@section('content')
<div class="auth-shell min-h-screen">
    <div class="mx-auto grid min-h-screen w-full max-w-7xl grid-cols-1 items-center gap-10 px-5 py-10 sm:px-8 lg:grid-cols-[0.95fr_1.05fr] lg:px-10">
        
        {{-- Left side: Registration form --}}
        <section class="mx-auto w-full max-w-lg">
            <div class="mb-8 text-center lg:text-left">
                {{-- Mobile logo link --}}
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200 lg:hidden">
                    <i class="fas fa-microchip text-emerald-500"></i>
                    SensorHub
                </a>
                <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Create your workspace</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">Start building with SensorHub</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Create an account to save projects, follow tutorials, and organize your sensor learning path.</p>
            </div>

            {{-- Registration form card --}}
            <div class="auth-panel rounded-3xl p-7 sm:p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf {{-- Security token --}}

                    {{-- Name field --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Full name</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input id="name" name="name" type="text" required
                                class="auth-input w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('name') border-red-500 @enderror"
                                placeholder="Juan Dela Cruz"
                                value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email field --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Email address</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" name="email" type="email" required
                                class="auth-input w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('email') border-red-500 @enderror"
                                placeholder="you@example.com"
                                value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role dropdown (User/Admin) --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Account type</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-id-badge"></i>
                            </span>
                            <select id="role" name="role" required
                                class="auth-input w-full appearance-none rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-12 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('role') border-red-500 @enderror">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password fields (side by side) --}}
                    <div class="grid gap-5 sm:grid-cols-2">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Password</label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" name="password" type="password" required
                                    class="auth-input w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('password') border-red-500 @enderror"
                                    placeholder="Minimum 8 characters">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Confirm password</label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="auth-input w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    placeholder="Repeat password">
                            </div>
                        </div>
                    </div>

                    {{-- Password tip --}}
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-900/70 dark:text-slate-300">
                        <i class="fas fa-circle-info mr-2 text-emerald-600 dark:text-emerald-300"></i>
                        Use at least 8 characters for better account security.
                    </div>

                    {{-- Submit button --}}
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-4 font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800 dark:bg-emerald-500 dark:text-slate-950 dark:hover:bg-emerald-400">
                        <i class="fas fa-user-plus"></i>
                        Create account
                    </button>
                </form>

                {{-- Login link --}}
                <div class="mt-7 border-t border-slate-200 pt-6 text-center text-sm text-slate-600 dark:border-slate-800 dark:text-slate-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-300 dark:hover:text-emerald-200">
                        Sign in
                    </a>
                </div>
            </div>

            {{-- Copyright text --}}
            <p class="mt-8 text-center text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                &copy; {{ date('Y') }} SensorHub
            </p>
        </section>

        {{-- Right side: Feature highlights (hidden on mobile) --}}
        <section class="hidden overflow-hidden rounded-[2rem] bg-slate-950 p-10 text-white shadow-2xl lg:block">
            <div class="relative min-h-[720px]">
                <div class="absolute inset-0 bg-[linear-gradient(145deg,rgba(16,185,129,0.24),transparent_42%),linear-gradient(315deg,rgba(59,130,246,0.22),transparent_45%)]"></div>
                <div class="relative z-10 flex min-h-[720px] flex-col justify-between">
                    <div>
                        {{-- Brand logo --}}
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold tracking-wide text-white/90">
                            <i class="fas fa-microchip text-emerald-300"></i>
                            SensorHub
                        </a>

                        {{-- Hero text --}}
                        <div class="mt-16 max-w-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">Learn. Build. Share.</p>
                            <h1 class="mt-5 text-5xl font-bold leading-tight">A focused home for sensor learning.</h1>
                            <p class="mt-6 text-lg leading-8 text-slate-200">Organize projects, explore practical guides, and keep the materials you need close when you are ready to build.</p>
                        </div>
                    </div>

                    {{-- Feature list --}}
                    <div class="space-y-4">
                        <div class="feature-row rounded-2xl p-5">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-200">
                                    <i class="fas fa-book-open"></i>
                                </span>
                                <div>
                                    <p class="font-semibold">Structured sensor guides</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-300">Browse clear references for modules, wiring, and project ideas.</p>
                                </div>
                            </div>
                        </div>

                        <div class="feature-row rounded-2xl p-5">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-400/15 text-blue-200">
                                    <i class="fas fa-folder-open"></i>
                                </span>
                                <div>
                                    <p class="font-semibold">Saved project flow</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-300">Keep favorite builds and tutorials ready for your next session.</p>
                                </div>
                            </div>
                        </div>

                        <div class="feature-row rounded-2xl p-5">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cyan-400/15 text-cyan-200">
                                    <i class="fas fa-lightbulb"></i>
                                </span>
                                <div>
                                    <p class="font-semibold">Ideas that keep moving</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-300">Share suggestions and discover practical inspiration from the platform.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection