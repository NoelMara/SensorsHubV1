@extends('layouts.app')

@section('title', 'Edit Suggestion')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Back Link -->
    <a href="{{ route('dashboard.suggestions') }}"
       class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary mb-6 transition">
        <i class="fas fa-arrow-left mr-2"></i> Back to My Suggestions
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Edit Suggestion</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            <i class="fas fa-info-circle mr-1"></i>
            You can only edit suggestions that are still <span class="font-semibold text-yellow-600">pending</span> review.
        </p>

        @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-5 py-4 rounded-lg">
            <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('dashboard.suggestions.update', $suggestion) }}">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Project Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           value="{{ old('title', $suggestion->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                              placeholder="Describe your project idea in detail...">{{ old('description', $suggestion->description) }}</textarea>
                </div>

                <!-- Difficulty + Sensor Type -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty Level</label>
                        <select name="difficulty"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select difficulty</option>
                            <option value="Beginner"     {{ old('difficulty', $suggestion->difficulty) == 'Beginner'     ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('difficulty', $suggestion->difficulty) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced"     {{ old('difficulty', $suggestion->difficulty) == 'Advanced'     ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type</label>
                        <input type="text" name="sensor_type"
                               value="{{ old('sensor_type', $suggestion->sensor_type) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="e.g., DHT11, HC-SR04">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('dashboard.suggestions') }}"
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection