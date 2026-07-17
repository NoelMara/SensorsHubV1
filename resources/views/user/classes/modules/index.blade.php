@extends('layouts.app')

@section('title', 'Modules - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Modules</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }}</p>
    </div>

    @if($modules->count() > 0)
        <div class="space-y-4">
            @foreach($modules as $module)
                <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $module->order }}. {{ $module->title }}</h3>
                            @if($module->content)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($module->content, 100) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($module->file_path)
                                <i class="fas fa-paperclip text-gray-400"></i>
                            @endif
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Modules Yet</h3>
            <p class="text-gray-500">Check back later!</p>
        </div>
    @endif

    @if($modules->hasPages())
        <div class="mt-6">{{ $modules->links() }}</div>
    @endif
</div>
@endsection