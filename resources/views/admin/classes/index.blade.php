@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">My Classes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $classes->total() }} {{ Str::plural('class', $classes->total()) }}</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium flex-shrink-0 shadow-sm">
            <i class="fas fa-plus mr-1.5"></i> Create Class
        </a>
    </div>

    @if($classes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($classes as $class)
                <a href="{{ route('admin.classes.show', $class) }}" 
                   class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-primary/30 dark:hover:border-primary/30 transition group">
                    
                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chalkboard text-primary text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary transition truncate">{{ $class->name }}</h3>
                        </div>
                        @if($class->section)
                            <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                Block {{ $class->section }}
                            </span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-users text-gray-400"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $class->students->count() }}</span>
                                students
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-tasks text-gray-400"></i>
                               <span class="font-medium text-gray-700 dark:text-gray-300">{{ $class->assessments()->count() }}</span>
                                assessments
                            </span>
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 rounded-xl px-4 py-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Class Code</p>
                                <p class="text-lg font-bold text-primary tracking-[0.15em]">{{ $class->code }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center group-hover:bg-primary transition">
                                <i class="fas fa-arrow-right text-primary group-hover:text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($classes->hasPages())
            <div class="mt-8">{{ $classes->links() }}</div>
        @endif
    @else
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-chalkboard text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">No Classes Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Create your first class and share the code with students!</p>
            <a href="{{ route('admin.classes.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                <i class="fas fa-plus mr-1.5"></i> Create Class
            </a>
        </div>
    @endif
</div>
@endsection