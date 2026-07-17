@extends('layouts.app')

@section('title', 'Import Modules - ' . $class->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.modules.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Modules
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Import Modules</h1>
        <p class="text-gray-600 dark:text-gray-400">Copy modules from another class to {{ $class->name }}</p>
    </div>

    @if($otherClasses->count() > 0)
        <div class="space-y-4">
            @foreach($otherClasses as $otherClass)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $otherClass->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $otherClass->modules()->count() }} modules</p>
                        </div>
                        <form method="POST" action="{{ route('admin.classes.modules.copy', $class) }}">
                            @csrf
                            <input type="hidden" name="from_class" value="{{ $otherClass->id }}">
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                <i class="fas fa-download mr-1"></i> Import
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-copy text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Other Classes</h3>
            <p class="text-gray-500">You don't have any other classes with modules to import from.</p>
        </div>
    @endif
</div>
@endsection