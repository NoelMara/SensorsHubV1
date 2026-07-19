@extends('layouts.app')

@section('title', 'Edit Class')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Class
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Edit Class</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update class details.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Class Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $class->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('name') border-red-500 @enderror"
                            placeholder="e.g., PF 101 Block 2-A">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Block</label>
                        <input type="text" name="section" id="section" value="{{ old('section', $class->section) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                            placeholder="e.g., 2-A">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none"
                            placeholder="Brief description of the class...">{{ old('description', $class->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.classes.show', $class) }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection