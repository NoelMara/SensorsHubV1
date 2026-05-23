@extends('layouts.app')

@section('title', 'Edit Sensor')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-8">
        <a href="{{ route('admin.sensors.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Sensors
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Edit Sensor</h1>
        <p class="text-gray-600 dark:text-gray-400">Update sensor details</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('admin.sensors.update', $sensor) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Sensor Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sensor Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $sensor->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $sensor->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- How It Works --}}
                <div>
                    <label for="how_it_works" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        How It Works <span class="text-red-500">*</span>
                    </label>
                    <textarea name="how_it_works" id="how_it_works" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('how_it_works') border-red-500 @enderror">{{ old('how_it_works', $sensor->how_it_works) }}</textarea>
                    @error('how_it_works')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Use Cases --}}
                <div>
                    <label for="use_cases" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Use Cases <span class="text-red-500">*</span>
                    </label>
                    <textarea name="use_cases" id="use_cases" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('use_cases') border-red-500 @enderror">{{ old('use_cases', $sensor->use_cases) }}</textarea>
                    @error('use_cases')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Components Needed --}}
                <div>
                    <label for="components_needed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Components Needed <span class="text-red-500">*</span>
                    </label>
                    <textarea name="components_needed" id="components_needed" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('components_needed') border-red-500 @enderror">{{ old('components_needed', $sensor->components_needed) }}</textarea>
                    @error('components_needed')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $sensor->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                        Active (visible to users)
                    </label>
                </div>

            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    Update Sensor
                </button>
                <a href="{{ route('admin.sensors.index') }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-center">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

@endsection