@extends('layouts.app')

@php($isSuperAdminLogin = ($loginMode ?? 'default') === 'super_admin')

@section('title', $isSuperAdminLogin ? 'Faculty Head Login' : 'Login')

@push('styles')
<style>
    .auth-shell {
        background:
            radial-gradient(circle at 15% 20%, rgba(16, 185, 129, 0.12), transparent 28%),
            radial-gradient(circle at 88% 12%, rgba(59, 130, 246, 0.16), transparent 30%),
            linear-gradient(135deg, #f8fafc 0%, #eef6f2 48%, #f7fafc 100%);
    }

    .dark .auth-shell {
        background:
            radial-gradient(circle at 15% 20%, rgba(16, 185, 129, 0.12), transparent 28%),
            radial-gradient(circle at 88% 12%, rgba(59, 130, 246, 0.12), transparent 30%),
            linear-gradient(135deg, #0f172a 0%, #111827 48%, #0b1120 100%);
    }

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

    .auth-input {
        transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
    }

    .auth-input:focus {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14);
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="auth-shell min-h-screen">
    <div class="mx-auto grid min-h-screen w-full max-w-7xl grid-cols-1 items-center gap-10 px-5 py-10 sm:px-8 lg:grid-cols-[1.05fr_0.95fr] lg:px-10">
        <section class="hidden overflow-hidden rounded-[2rem] bg-slate-950 p-10 text-white shadow-2xl lg:block">
            <div class="relative min-h-[650px]">
                <div class="absolute inset-0 bg-[linear-gradient(145deg,rgba(16,185,129,0.24),transparent_42%),linear-gradient(315deg,rgba(59,130,246,0.24),transparent_45%)]"></div>
                <div class="relative z-10 flex min-h-[650px] flex-col justify-between">
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold tracking-wide text-white/90">
                            <i class="fas fa-microchip text-emerald-300"></i>
                            SensorHub
                        </a>

                        <div class="mt-16 max-w-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">
                                {{ $isSuperAdminLogin ? 'Platform command' : 'Project workspace' }}
                            </p>
                            <h1 class="mt-5 text-5xl font-bold leading-tight">
                                {{ $isSuperAdminLogin ? 'Manage SensorHub with clarity.' : 'Build smarter sensor projects.' }}
                            </h1>
                            <p class="mt-6 text-lg leading-8 text-slate-200">
                                {{ $isSuperAdminLogin ? 'Review accounts, roles, and platform activity from a secure administrator entry point.' : 'Access guides, save project ideas, and continue learning with a workspace designed for makers.' }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="metric-card rounded-2xl p-4">
                                <p class="text-2xl font-bold">{{ ($stats['projects'] ?? 0) }}+</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-300">Guides</p>
                            </div>
                            <div class="metric-card rounded-2xl p-4">
                                <p class="text-2xl font-bold">24/7</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-300">Access</p>
                            </div>
                            <div class="metric-card rounded-2xl p-4">
                                <p class="text-2xl font-bold">IoT</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-300">Focused</p>
                            </div>
                        </div>

                        <div class="mt-8 space-y-4 text-sm text-slate-200">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-200">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span>{{ $isSuperAdminLogin ? 'Protected access for elevated accounts' : 'Continue saved builds and learning paths' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-400/15 text-blue-200">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <span>{{ $isSuperAdminLogin ? 'Monitor users and content activity' : 'Find sensor references faster' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto w-full max-w-md">
            <div class="mb-8 text-center lg:text-left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200 lg:hidden">
                    <i class="fas fa-microchip text-emerald-500"></i>
                    SensorHub
                </a>
                <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">
                    {{ session('require_verification') ? 'Email verification' : ($isSuperAdminLogin ? 'Faculty Head access' : 'Welcome back') }}
                </p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">
                    {{ session('require_verification') ? 'Enter your security code' : ($isSuperAdminLogin ? 'Sign in securely' : 'Sign in to your account') }}
                </h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">
                    {{ session('require_verification') ? 'We sent a 6-digit code to your email address.' : ($isSuperAdminLogin ? 'Use your authorized SensorHub administrator credentials.' : 'Pick up where you left off with sensors, tutorials, and saved projects.') }}
                </p>
            </div>

            @if(session('require_verification'))
                <div class="auth-panel rounded-3xl p-7 sm:p-8">
                    <div class="mb-7 flex items-start gap-4 rounded-2xl bg-emerald-50 p-4 text-emerald-950 dark:bg-emerald-400/10 dark:text-emerald-100">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                            <i class="fas fa-envelope-open-text"></i>
                        </span>
                        <div>
                            <p class="font-semibold">Verification required</p>
                            <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-200">{{ session('user_email') }}</p>
                        </div>
                    </div>

                    {{-- Success message (resend confirmation) --}}
                    @if(session('message'))
                        <div class="mb-5 flex items-center gap-3 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">
                            <i class="fas fa-check-circle"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    {{-- Resend rate limit error --}}
                    @error('email')
                        <div class="mb-5 flex items-center gap-3 rounded-2xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-600 dark:bg-red-400/10 dark:text-red-400">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <form method="POST" action="{{ route('login.verify') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('user_email') }}">

                        <div>
                            <label for="verification_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Verification code</label>
                            <input
                                id="verification_code"
                                name="verification_code"
                                type="text"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                required
                                class="auth-input mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-4 text-center text-3xl font-bold tracking-[0.35em] text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('verification_code') border-red-500 @enderror"
                                placeholder="000000"
                                autocomplete="one-time-code"
                            >
                            {{-- Wrong code error --}}
                            @error('verification_code')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-4 font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800 dark:bg-emerald-500 dark:text-slate-950 dark:hover:bg-emerald-400">
                            <i class="fas fa-shield-halved"></i>
                            Verify and continue
                        </button>
                    </form>

                    <div class="mt-6 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-5 text-sm dark:border-slate-800 sm:flex-row">
                        <form method="POST" action="{{ route('login.resend') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('user_email') }}">
                            <button type="submit" class="font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-300 dark:hover:text-emerald-200">
                                <i class="fas fa-rotate-right mr-1"></i>Resend code
                            </button>
                        </form>
                        <a href="{{ $isSuperAdminLogin ? route('super-admin.login') : route('login') }}" class="font-semibold text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            Back to login
                        </a>
                    </div>
                </div>
            @else
                <div class="auth-panel rounded-3xl p-7 sm:p-8">
                    <form method="POST" action="{{ $isSuperAdminLogin ? route('super-admin.login.submit') : route('login') }}" class="space-y-5">
                        @csrf

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
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Password</label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" name="password" type="password" required
                                    class="auth-input w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-12 text-slate-950 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white @error('password') border-red-500 @enderror"
                                    placeholder="Enter your password">
                                <button type="button" id="togglePassword" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember" class="flex cursor-pointer items-center gap-3 text-sm font-medium text-slate-600 dark:text-slate-300">
                                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-4 font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800 dark:bg-emerald-500 dark:text-slate-950 dark:hover:bg-emerald-400">
                            <i class="fas {{ $isSuperAdminLogin ? 'fa-user-shield' : 'fa-right-to-bracket' }}"></i>
                            {{ $isSuperAdminLogin ? 'Enter Faculty Head' : 'Sign in' }}
                        </button>
                    </form>

                    <div class="mt-7 border-t border-slate-200 pt-6 text-center text-sm text-slate-600 dark:border-slate-800 dark:text-slate-400">
                        @if($isSuperAdminLogin)
                            Need a regular account?
                            <a href="{{ route('login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-300 dark:hover:text-emerald-200">
                                User/Admin login
                            </a>
                        @else
                            New to SensorHub?
                            <a href="{{ route('register') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-900 dark:text-emerald-300 dark:hover:text-emerald-200">
                                Create an account
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <p class="mt-8 text-center text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                &copy; {{ date('Y') }} SensorHub
            </p>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const codeInput = document.getElementById('verification_code');
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    }

    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
</script>
@endpush