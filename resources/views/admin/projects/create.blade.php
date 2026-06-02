@extends('layouts.app')

@section('title', 'Add Project')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="mb-8">

        <a href="{{ route(($prefix ?? 'admin') . '.projects.index') }}"
           class="text-primary hover:underline mb-2 inline-block">

            <i class="fas fa-arrow-left mr-1"></i>
            Back
        </a>

        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
            Add Project
        </h1>

    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">

        <form method="POST" action="{{ route(($prefix ?? 'admin') . '.projects.store') }}">
            @csrf

            <div class="space-y-6">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Title *
                    </label>

                    <input type="text"
                           name="title"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description *
                    </label>

                    <textarea name="description"
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                {{-- Sensor --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sensor *
                    </label>

                    <select name="sensor_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">

                        @foreach($sensors as $sensor)
                            <option value="{{ $sensor->id }}">
                                {{ $sensor->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Difficulty --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Difficulty *
                    </label>

                    <select name="difficulty"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">

                        <option>Beginner</option>
                        <option>Intermediate</option>
                        <option>Advanced</option>

                    </select>
                </div>

                {{-- Components Needed --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Components Needed *
                    </label>

                    <textarea name="components_needed"
                              rows="3"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                {{-- Instructions --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instructions *
                    </label>

                    <textarea name="instructions"
                              rows="6"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                {{-- Active Toggle --}}
                <div class="flex items-center">

                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           checked
                           class="h-4 w-4 text-primary">

                    <label class="ml-2 text-sm">
                        Active
                    </label>

                </div>

            </div>

            {{-- Buttons --}}
            <div class="mt-8 flex space-x-4">

                <button type="submit"
                        class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    Create
                </button>

                <a href="{{ route(($prefix ?? 'admin') . '.projects.index') }}"
                   class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg text-center">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>
@endsection