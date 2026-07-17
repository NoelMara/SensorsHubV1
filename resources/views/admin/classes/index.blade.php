@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-2">My Classes</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your classes and share codes with students.</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> Create Class
        </a>
    </div>

    @if($classes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($classes as $class)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $class->name }}</h3>
                            @if($class->section)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $class->section }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 whitespace-nowrap ml-2">
                            {{ $class->students->count() }} students
                        </span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Class Code:</p>
                        <p class="text-xl font-bold text-primary tracking-wider">{{ $class->code }}</p>
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('admin.classes.show', $class) }}" 
                            class="block w-full text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-semibold">
                            <i class="fas fa-eye mr-1"></i> View Class
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($classes->hasPages())
            <div class="flex justify-center mt-6 mb-8">
                <div class="inline-flex rounded-lg shadow-sm">
                    {{ $classes->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <i class="fas fa-chalkboard text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Classes Yet</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Create your first class and share the code with students!</p>
            <a href="{{ route('admin.classes.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-plus mr-2"></i> Create Class
            </a>
        </div>
    @endif
</div>
@endsection