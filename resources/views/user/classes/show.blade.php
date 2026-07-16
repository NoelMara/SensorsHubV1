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

    <!-- Modules -->
    @php $modules = $class->modules()->where('is_published', true)->orderBy('order')->get(); @endphp
    @if($modules->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Modules</h2>
        <div class="space-y-3">
            @foreach($modules as $module)
                <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $module->order }}. {{ $module->title }}</h3>
                            @if($module->content)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($module->content, 80) }}</p>
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
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to My Classes
        </a>
    </div>
</div>
@endsection