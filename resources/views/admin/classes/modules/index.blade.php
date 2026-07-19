@extends('layouts.app')

@section('title', 'Modules - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Modules - {{ $class->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $modules->total() }} {{ Str::plural('module', $modules->total()) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.classes.modules.create', $class) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> Add Module
                </a>
                <a href="{{ route('admin.classes.modules.import', $class) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap">
                    <i class="fas fa-download mr-1"></i> Import
                </a>
            </div>
        </div>
    </div>

    @if($modules->count() > 0)
        <div class="space-y-4">
            @foreach($modules as $module)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $module->order }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate" title="{{ $module->title }}">
                                    {{ Str::limit($module->title, 60) }}
                                </h3>
                                @if($module->is_published)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 flex-shrink-0">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 flex-shrink-0">Draft</span>
                                @endif
                                @if($module->file_path)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 flex-shrink-0">
                                        <i class="fas fa-paperclip mr-1"></i>File
                                    </span>
                                @endif
                            </div>
                            @if($module->content)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 line-clamp-2">{{ Str::limit($module->content, 150) }}</p>
                            @endif
                            @if($module->file_name)
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-file mr-1"></i>{{ $module->file_name }}
                                    @if($module->file_size)
                                        <span class="ml-1">({{ $module->file_size > 1048576 ? number_format($module->file_size / 1048576, 1) . ' MB' : number_format($module->file_size / 1024, 1) . ' KB' }})</span>
                                    @endif
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" 
                               class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Preview Module">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.classes.modules.edit', [$class, $module]) }}" 
                               class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Edit Module">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.classes.modules.destroy', [$class, $module]) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this module?');">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition"
                                        title="Delete Module">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Modules Yet</h3>
            <p class="text-gray-500">Add your first module or import from another class!</p>
        </div>
     @endif

    @if($modules->hasPages())
        <div class="mt-6">{{ $modules->links() }}</div>
    @endif
</div>
@endsection