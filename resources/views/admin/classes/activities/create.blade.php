@extends('layouts.app')

@section('title', 'Add Activity')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Activities
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Add Activity</h1>
        <p class="text-gray-600 dark:text-gray-400">Create a new activity for {{ $class->name }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('admin.classes.activities.store', $class) }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Activity Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Sensor Wiring Practice">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                        placeholder="Brief description of the activity...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instructions
                    </label>
                    <textarea name="instructions" id="instructions" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                        placeholder="Step-by-step instructions for students...">{{ old('instructions') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Points <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="points" id="points" value="{{ old('points', 10) }}" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Due Date
                        </label>
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white [color-scheme:dark]">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1" checked
                        class="h-4 w-4 text-primary rounded border-gray-300">
                    <label for="is_published" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Publish immediately (visible to students)
                    </label>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i> Save Activity
                </button>
                <a href="{{ route('admin.classes.activities.index', $class) }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg text-center hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection