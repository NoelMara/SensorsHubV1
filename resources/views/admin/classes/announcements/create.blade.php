@extends('layouts.app')

@section('title', 'New Announcement - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.announcements.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">New Announcement</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Post an announcement for {{ $class->name }}.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.classes.announcements.store', $class) }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Content</label>
                        <textarea name="content" id="content" rows="8" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Publish immediately (visible to students)</label>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-bullhorn mr-1.5"></i> Post Announcement
                    </button>
                    <a href="{{ route('admin.classes.announcements.index', $class) }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection