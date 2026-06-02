@extends('layouts.app')

@section('title', 'Add Video')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-8">
        <a href="{{ route(($prefix ?? 'admin') . '.videos.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Videos
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Add Video</h1>
        <p class="text-gray-600 dark:text-gray-400">Add a new tutorial video</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ ($prefix ?? 'admin') === 'super-admin' ? route('super-admin.content.store', 'videos') : route('admin.videos.store') }}">
            @csrf

            <div class="space-y-6">

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" id="slug" required value="{{ old('slug') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('slug') border-red-500 @enderror">
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- YouTube Link --}}
                <div>
                    <label for="youtube_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        YouTube Link <span class="text-red-500">*</span>
                    </label>
                    <input type="url" name="youtube_link" id="youtube_link" required value="{{ old('youtube_link') }}" placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('youtube_link') border-red-500 @enderror">
                    @error('youtube_link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sensor --}}
                <div>
                    <label for="sensor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Related Sensor
                    </label>
                    <select name="sensor_id" id="sensor_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('sensor_id') border-red-500 @enderror">
                        <option value="">-- Select Sensor --</option>
                        @foreach($sensors as $sensor)
                            <option value="{{ $sensor->id }}" {{ old('sensor_id') == $sensor->id ? 'selected' : '' }}>
                                {{ $sensor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('sensor_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('category') border-red-500 @enderror">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                        Active (visible to users)
                    </label>
                </div>

            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Create Video
                </button>
                <a href="{{ route(($prefix ?? 'admin') . '.videos.index') }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-center">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection