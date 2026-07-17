@extends('layouts.app')

@section('title', 'Import Announcements - ' . $class->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.announcements.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Import Announcements</h1>
        <p class="text-gray-600 dark:text-gray-400">Select announcements to copy to {{ $class->name }}</p>
    </div>

    @if($otherClasses->count() > 0)
        @foreach($otherClasses as $otherClass)
            @if($otherClass->announcements()->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $otherClass->name }}</h3>
                <form method="POST" action="{{ route('admin.classes.announcements.copy', $class) }}">
                    @csrf
                    <input type="hidden" name="from_class" value="{{ $otherClass->id }}">
                    <div class="space-y-3 mb-4">
                        @foreach($otherClass->announcements as $announcement)
                            <label class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input type="checkbox" name="announcements[]" value="{{ $announcement->id }}" checked
                                    class="h-4 w-4 text-primary rounded border-gray-300">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $announcement->title }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($announcement->content, 60) }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                        <i class="fas fa-download mr-1"></i> Import Selected
                    </button>
                </form>
            </div>
            @endif
        @endforeach
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-copy text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Other Classes</h3>
            <p class="text-gray-500">You don't have any other classes with announcements to import from.</p>
        </div>
    @endif
</div>
@endsection