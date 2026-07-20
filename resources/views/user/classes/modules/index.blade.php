@extends('layouts.app')

@section('title', 'Modules - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(auth()->user()->isInstructor() || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.classes.modules.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Modules
        </a>
    @else
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
    @endif

    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Modules</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">{{ $class->name }} · {{ $modules->total() }} {{ Str::plural('module', $modules->total()) }}</p>

    @if($modules->count() > 0)
        <div class="space-y-3">
            @foreach($modules as $module)
                <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" 
                   class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:border-primary/30 dark:hover:border-primary/30 hover:shadow-md transition group">
                    
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-500 dark:text-gray-400">
                        {{ $module->order }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $module->title }}</h3>
                        @if($module->content)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($module->content, 80) }}</p>
                        @endif
                        @if($module->file_name)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                <i class="fas fa-paperclip mr-1"></i>{{ $module->file_name }}
                            </p>
                        @endif
                    </div>

                    <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 group-hover:text-primary transition flex-shrink-0 text-sm"></i>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-book-open text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Modules Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check back later!</p>
        </div>
    @endif

    @if($modules->hasPages())
        <div class="mt-6">{{ $modules->links() }}</div>
    @endif
</div>
@endsection