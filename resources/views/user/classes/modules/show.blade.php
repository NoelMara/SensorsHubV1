@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(auth()->user()->isInstructor() || auth()->user()->isAdministrator())
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
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden" 
         x-data="{ clicked: false }">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
            <i class="fas fa-paperclip text-gray-400 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Attachment</h2>
        </div>
        <a href="{{ $module->file_path }}" download
           @click="clicked = true"
           :class="clicked ? 'pointer-events-none opacity-60' : ''"
           class="flex items-center gap-4 px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
            <div class="w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $icon }} text-primary text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ $module->file_name ?? 'Download File' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    @if($module->file_size)
                        {{ $module->file_size > 1048576 ? number_format($module->file_size / 1048576, 1) . ' MB' : number_format($module->file_size / 1024, 1) . ' KB' }}
                        <span class="mx-1">·</span>
                    @endif
                    <span x-show="!clicked">Click to download</span>
                    <span x-show="clicked" class="text-green-600 dark:text-green-400 font-medium">Download started ✓</span>
                </p>
                <p x-show="clicked" @click.stop="clicked = false" class="text-xs text-primary hover:underline mt-1 cursor-pointer">Download again</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-download text-primary text-xs"></i>
            </div>
        </a>
    </div>
    @endif
</div>
@endsection