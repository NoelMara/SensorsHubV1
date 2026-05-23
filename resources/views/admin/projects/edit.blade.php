@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="mb-8">

        <a href="{{ route('admin.projects.index') }}"
           class="text-primary hover:underline mb-2 inline-block">

            <i class="fas fa-arrow-left mr-1"></i>
            Back to Projects
        </a>

        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
            Edit Project
        </h1>

        <p class="text-gray-600 dark:text-gray-400">
            Update the project details and visibility settings.
        </p>

    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">

        <form method="POST"
              action="{{ route('admin.projects.update', $project) }}">

            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Title --}}
                <div>
                    <label for="title"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Title <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="title"
                           id="title"
                           required
                           value="{{ old('title', $project->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">

                    @error('title')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Description <span class="text-red-500">*</span>
                    </label>

                    <textarea name="description"
                              id="description"
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $project->description) }}</textarea>

                    @error('description')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Sensor Selection --}}
                <div>
                    <label for="sensor_id"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Sensor <span class="text-red-500">*</span>
                    </label>

                    <select name="sensor_id"
                            id="sensor_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('sensor_id') border-red-500 @enderror">

                        @foreach($sensors as $sensor)
                            <option value="{{ $sensor->id }}"
                                {{ (string) old('sensor_id', $project->sensor_id) === (string) $sensor->id ? 'selected' : '' }}>

                                {{ $sensor->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('sensor_id')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Difficulty --}}
                <div>
                    <label for="difficulty"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Difficulty <span class="text-red-500">*</span>
                    </label>

                    <select name="difficulty"
                            id="difficulty"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('difficulty') border-red-500 @enderror">

                        @foreach(['Beginner', 'Intermediate', 'Advanced'] as $difficulty)
                            <option value="{{ $difficulty }}"
                                {{ old('difficulty', $project->difficulty) === $difficulty ? 'selected' : '' }}>

                                {{ $difficulty }}
                            </option>
                        @endforeach

                    </select>

                    @error('difficulty')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Components Needed --}}
                <div>
                    <label for="components_needed"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Components Needed <span class="text-red-500">*</span>
                    </label>

                    <textarea name="components_needed"
                              id="components_needed"
                              rows="3"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('components_needed') border-red-500 @enderror">{{ old('components_needed', $project->components_needed) }}</textarea>

                    @error('components_needed')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Instructions --}}
                <div>
                    <label for="instructions"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Instructions <span class="text-red-500">*</span>
                    </label>

                    <textarea name="instructions"
                              id="instructions"
                              rows="6"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('instructions') border-red-500 @enderror">{{ old('instructions', $project->instructions) }}</textarea>

                    @error('instructions')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Visibility Settings --}}
                <div class="space-y-3">

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', $project->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">

                        <label for="is_active"
                               class="ml-2 block text-sm text-gray-900 dark:text-white">

                            Active (visible to users)
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_featured"
                               id="is_featured"
                               value="1"
                               {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">

                        <label for="is_featured"
                               class="ml-2 block text-sm text-gray-900 dark:text-white">

                            Featured project
                        </label>
                    </div>

                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex space-x-4">

                <button type="submit"
                        class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">

                    <i class="fas fa-save mr-2"></i>
                    Update Project
                </button>

                <a href="{{ route('admin.projects.index') }}"
                   class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-center">

                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>
@endsection