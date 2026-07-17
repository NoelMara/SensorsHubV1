@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $module->title }}</h1>
    </div>

    @if($module->content)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Module Content</h2>
        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-line">
            {{ $module->content }}
        </div>
    </div>
    @endif

    @if($module->file_path)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Attachment</h2>
        <a href="{{ $module->file_path }}" target="_blank" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-download mr-2"></i> {{ $module->file_name ?? 'Download File' }}
        </a>
    </div>
    @endif
</div>
@endsection