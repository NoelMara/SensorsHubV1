@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.classes.modules.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-8">
            <i class="fas fa-arrow-left mr-1"></i> Back to Modules
        </a>
    @else
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-8">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
    @endif

    {{-- Content Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
            <i class="fas fa-book-open text-primary text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $module->title }}</h2>
            <span class="ml-auto text-xs text-gray-400">{{ $class->name }}</span>
        </div>
        <div class="p-6 sm:p-8">
            @if($module->content)
                <div class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $module->content }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-sm text-gray-400 dark:text-gray-500">No additional content. Check the attachment below.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Attachment --}}
    @if($module->file_path)
    @php
        $extension = strtolower(pathinfo($module->file_name, PATHINFO_EXTENSION));
        $icon = match($extension) {
            'pdf' => 'fa-file-pdf',
            'doc', 'docx' => 'fa-file-word',
            default => 'fa-file-alt'
        };
        $color = match($extension) {
            'pdf' => 'text-red-500 dark:text-red-400',
            'doc', 'docx' => 'text-blue-500 dark:text-blue-400',
            default => 'text-blue-500 dark:text-blue-400'
        };
        $bg = match($extension) {
            'pdf' => 'bg-red-50 dark:bg-red-900/20',
            'doc', 'docx' => 'bg-blue-50 dark:bg-blue-900/20',
            default => 'bg-gray-50 dark:bg-gray-700/50'
        };
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
            <i class="fas fa-paperclip text-gray-400 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Attachment</h2>
        </div>
        <a href="{{ $module->file_path }}" target="_blank" 
           class="flex items-center gap-4 px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
            <div class="w-12 h-12 rounded-xl {{ $bg }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <i class="fas {{ $icon }} {{ $color }} text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-primary transition truncate">
                    {{ $module->file_name ?? 'Download File' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    @if($module->file_size)
                        {{ $module->file_size > 1048576 ? number_format($module->file_size / 1048576, 1) . ' MB' : number_format($module->file_size / 1024, 1) . ' KB' }}
                        <span class="mx-1">·</span>
                    @endif
                    Click to download
                </p>
            </div>
            <div class="w-8 h-8 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0 group-hover:bg-primary transition">
                <i class="fas fa-arrow-down text-primary group-hover:text-white text-xs"></i>
            </div>
        </a>
    </div>
    @endif
</div>
@endsection