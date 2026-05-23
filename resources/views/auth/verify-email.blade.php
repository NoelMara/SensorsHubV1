@extends('layouts.app')

@section('title', 'Verify Your Email Address')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Main Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">

        {{-- Header Section --}}
        <div class="text-center mb-8">

            {{-- Icon --}}
            <div class="mb-6">
                <i class="fas fa-envelope-open-text text-6xl text-primary"></i>
            </div>

            {{-- Title --}}
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                Verify Your Email Address
            </h1>

            {{-- Description --}}
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                We've sent a 6-digit verification code to
                <strong>{{ auth()->user()->email }}</strong>
            </p>

        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('message'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- VERIFY FORM --}}
        <form method="POST" action="{{ route('verification.verify') }}" class="space-y-6">
            @csrf

            {{-- CODE INPUT --}}
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Enter Verification Code
                </label>

                <input
                    type="text"
                    name="code"
                    id="code"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autocomplete="off"
                    placeholder="000000"
                    class="w-full px-4 py-3 text-center text-3xl font-bold tracking-widest border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                >

                {{-- Timer note --}}
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Code expires in 10 minutes
                </p>
            </div>

            {{-- SUBMIT BUTTON --}}
            <button type="submit"
                class="w-full bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">

                <i class="fas fa-check-circle mr-2"></i>
                Verify Email
            </button>

        </form>

        {{-- ACTIONS --}}
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">

            {{-- Resend code --}}
            <form method="POST" action="{{ route('verification.resend') }}" class="text-center mb-4">
                @csrf
                <button type="submit"
                    class="text-primary hover:text-blue-600 transition font-medium">

                    <i class="fas fa-redo mr-1"></i>
                    Resend Code
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit"
                    class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">

                    <i class="fas fa-arrow-left mr-1"></i>
                    Logout
                </button>
            </form>

        </div>

    </div>
</div>

@endsection


{{-- SCRIPTS --}}
@push('scripts')
<script>
    // Allow only numbers + limit to 6 digits
    document.getElementById('code').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });

    // Optional auto-detect full code (UX improvement)
    document.getElementById('code').addEventListener('input', function () {
        if (this.value.length === 6) {
            // Auto submit can be enabled here if needed
            // this.form.submit();
        }
    });
</script>
@endpush