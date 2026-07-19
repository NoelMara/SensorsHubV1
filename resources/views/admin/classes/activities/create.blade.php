@extends('layouts.app')

@section('title', 'Add Activity')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Activities
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Add Activity</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new activity for {{ $class->name }}.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.classes.activities.store', $class) }}">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Activity Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror"
                            placeholder="e.g., Sensor Wiring Practice">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('description') border-red-500 @enderror"
                            placeholder="Brief description of the activity...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instructions</label>
                        <textarea name="instructions" id="instructions" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('instructions') border-red-500 @enderror"
                            placeholder="Step-by-step instructions for students...">{{ old('instructions') }}</textarea>
                        @error('instructions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Points</label>
                            <input type="number" name="points" id="points" required min="1" value="{{ old('points', 10) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('points') border-red-500 @enderror">
                            @error('points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Due Date <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('due_date') border-red-500 @enderror">
                            @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Publish immediately (visible to students)</label>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Save Activity
                    </button>
                    <a href="{{ route('admin.classes.activities.index', $class) }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection