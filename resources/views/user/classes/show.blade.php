@extends('layouts.app')

@section('title', $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Classes
        </a>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $class->name }}</h1>
        @if($class->section)
            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">
                Block {{ $class->section }}
            </span>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Class Info</h2>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Instructor</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $class->instructor->name }}</p>
            </div>
            @if($class->description)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $class->description }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Class Code</p>
                <p class="text-2xl font-bold text-primary tracking-[0.3em]">{{ $class->code }}</p>
            </div>
        </div>
    </div>

    <div class="text-center">
        <form action="{{ route('dashboard.classes.join') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="code" value="{{ $class->code }}">
        </form>
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to My Classes
        </a>
    </div>
</div>
@endsection