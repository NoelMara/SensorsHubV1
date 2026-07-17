@extends('layouts.app')

@section('title', 'Add Module')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.modules.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Modules
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Add Module</h1>
        <p class="text-gray-600 dark:text-gray-400">Create a new module for {{ $class->name }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('admin.classes.modules.store', $class) }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Module Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Introduction to Sensors">
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Information <span class="text-gray-400 font-normal">- optional</span>
                    </label>
                    <textarea name="content" id="content" rows="8"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                        placeholder="Any extra instructions or notes for students...">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Attachment (PDF/Word) <span class="text-gray-400 font-normal">- optional</span>
                    </label>
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Upload a PDF or Word document. Max 10MB.</p>
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
                    <i class="fas fa-save mr-2"></i> Save Module
                </button>
                <a href="{{ route('admin.classes.modules.index', $class) }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg text-center hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection