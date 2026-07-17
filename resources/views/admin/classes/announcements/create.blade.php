@extends('layouts.app')

@section('title', 'New Announcement - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.classes.announcements.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">New Announcement</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }}</p>
    </div>

    <form action="{{ route('admin.classes.announcements.store', $class) }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
                <textarea name="content" rows="8" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                    class="rounded border-gray-300 dark:border-gray-600">
                <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Publish immediately (visible to students)</label>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.classes.announcements.index', $class) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                    Cancel
                </a>
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition text-sm">
                    <i class="fas fa-bullhorn mr-1"></i> Post Announcement
                </button>
            </div>
        </div>
    </form>
</div>
@endsection