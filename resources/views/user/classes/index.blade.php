@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">My Classes</h1>
        <p class="text-gray-600 dark:text-gray-400">Join a class using the code from your instructor.</p>
    </div>

    <!-- Join Class Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Join a Class</h2>
        <form method="POST" action="{{ route('dashboard.classes.join') }}" class="flex gap-3">
            @csrf
            <input type="text" name="code" required maxlength="6"
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-center text-xl font-bold tracking-[0.3em] uppercase"
                placeholder="Enter code">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-600 transition font-semibold">
                <i class="fas fa-plus mr-2"></i> Join
            </button>
        </form>
    </div>

    <!-- Enrolled Classes -->
    @if($classes->count() > 0)
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Enrolled Classes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($classes as $class)
                <a href="{{ route('dashboard.classes.show', $class) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $class->name }}</h3>
                        @if($class->section)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Block {{ $class->section }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-user mr-1"></i> {{ $class->instructor->name }}
                    </p>
                    @if($class->description)
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($class->description, 80) }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <i class="fas fa-chalkboard text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600 dark:text-gray-400 mb-2">No Classes Yet</h3>
            <p class="text-gray-500 dark:text-gray-400">Enter a class code above to join!</p>
        </div>
    @endif
</div>
@endsection