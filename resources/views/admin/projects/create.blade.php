@extends('layouts.app')

@section('title', 'Add Project')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route(($prefix ?? 'admin') . '.projects.index') }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Projects
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Add New Project</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new guided project tutorial.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ ($prefix ?? 'admin') === 'super-admin' ? route('super-admin.content.store', 'projects') : route('admin.projects.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="3" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sensor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sensor</label>
                        <select name="sensor_id" id="sensor_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('sensor_id') border-red-500 @enderror">
                            @foreach($sensors as $sensor)
                                <option value="{{ $sensor->id }}" {{ old('sensor_id') == $sensor->id ? 'selected' : '' }}>{{ $sensor->name }}</option>
                            @endforeach
                        </select>
                        @error('sensor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Difficulty</label>
                        <select name="difficulty" id="difficulty" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('difficulty') border-red-500 @enderror">
                            <option value="Beginner" {{ old('difficulty') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('difficulty') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('difficulty') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('difficulty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="components_needed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Components Needed</label>
                        <textarea name="components_needed" id="components_needed" rows="3" required placeholder="Arduino Uno, LED, Jumper Wires..."
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('components_needed') border-red-500 @enderror">{{ old('components_needed') }}</textarea>
                        @error('components_needed') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instructions</label>
                        <textarea name="instructions" id="instructions" rows="5" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('instructions') border-red-500 @enderror">{{ old('instructions') }}</textarea>
                        @error('instructions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Code <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="code" id="code" rows="6" placeholder="Paste your code here..."
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('code') border-red-500 @enderror">{{ old('code') }}</textarea>
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Featured</span>
                    </label>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Create Project
                    </button>
                    <a href="{{ route(($prefix ?? 'admin') . '.projects.index') }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection