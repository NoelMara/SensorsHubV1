@extends('layouts.app')

@section('title', 'Modules - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Modules - {{ $class->name }}</h1>
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
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $module->order }}. {{ $module->title }}
                            </h3>
                            @if($module->content)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($module->content, 100) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($module->is_published)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Draft</span>
                            @endif
                            <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" class="text-primary hover:text-blue-700 text-sm" title="View Module">
                                <i class="fas fa-eye"></i>
                            </a>
                             <a href="{{ route('admin.classes.modules.edit', [$class, $module]) }}" class="text-primary hover:text-blue-700 text-sm" title="Edit Module">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.classes.modules.destroy', [$class, $module]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
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