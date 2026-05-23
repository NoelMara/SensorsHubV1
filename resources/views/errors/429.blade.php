@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-500 mb-4">429</h1>
        <h2 class="text-2xl font-semibold mb-4">Too Many Attempts</h2>
        <p class="text-gray-600 mb-6">Please wait 1 minute before trying again.</p>
        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600">
            Back to Login
        </a>
    </div>
</div>
@endsection