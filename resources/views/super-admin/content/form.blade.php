@extends('layouts.app')

@php
    $isEdit = filled($item);
    $resourceName = Str::singular($title);
@endphp

@section('title', ($isEdit ? 'Edit ' : 'Add ') . $resourceName)

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('super-admin.' . $type . '.index') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Back to {{ $title }}
            </a>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">{{ $isEdit ? 'Edit' : 'Add' }} {{ $resourceName }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage this record directly from the super-admin workspace.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6 lg:p-8">
            <form method="POST" action="{{ $isEdit ? route('super-admin.content.update', [$type, $item->id]) : route('super-admin.content.store', $type) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                @if(in_array($type, ['sensors', 'products'], true))
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                    </div>
                @else
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                    </div>
                @endif

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" @if($type !== 'videos') required @endif>{{ old('description', $item->description ?? '') }}</textarea>
                </div>

                @if($type === 'sensors')
                    <div>
                        <label for="how_it_works" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">How It Works</label>
                        <textarea id="how_it_works" name="how_it_works" rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>{{ old('how_it_works', $item->how_it_works ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="use_cases" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Use Cases</label>
                        <textarea id="use_cases" name="use_cases" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>{{ old('use_cases', $item->use_cases ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="components_needed" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Components Needed</label>
                        <textarea id="components_needed" name="components_needed" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>{{ old('components_needed', $item->components_needed ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Image</label>
                        <input id="image" name="image" type="file" accept="image/*" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-800 dark:text-white">
                    </div>
                @endif

                @if($type === 'projects')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="difficulty" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Difficulty</label>
                            <select id="difficulty" name="difficulty" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                                @foreach(['Beginner', 'Intermediate', 'Advanced'] as $difficulty)
                                    <option value="{{ $difficulty }}" @selected(old('difficulty', $item->difficulty ?? '') === $difficulty)>{{ $difficulty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sensor_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sensor</label>
                            <select id="sensor_id" name="sensor_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                                @foreach($sensors as $sensor)
                                    <option value="{{ $sensor->id }}" @selected((int) old('sensor_id', $item->sensor_id ?? 0) === $sensor->id)>{{ $sensor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="components_needed" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Components Needed</label>
                        <textarea id="components_needed" name="components_needed" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>{{ old('components_needed', $item->components_needed ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="instructions" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Instructions</label>
                        <textarea id="instructions" name="instructions" rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>{{ old('instructions', $item->instructions ?? '') }}</textarea>
                    </div>
                    <label class="inline-flex items-center">
                        <input name="is_featured" type="checkbox" class="h-5 w-5 text-primary rounded" @checked(old('is_featured', $item->is_featured ?? false))>
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Featured project</span>
                    </label>
                @endif

                @if($type === 'products')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Price</label>
                            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $item->price ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <input id="category" name="category" type="text" value="{{ old('category', $item->category ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>
                        <div>
                            <label for="link" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Link</label>
                            <input id="link" name="link" type="url" value="{{ old('link', $item->link ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>
                    </div>
                @endif

                @if($type === 'videos')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="youtube_link" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">YouTube Link</label>
                            <input id="youtube_link" name="youtube_link" type="url" value="{{ old('youtube_link', $item->youtube_link ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <input id="category" name="category" type="text" value="{{ old('category', $item->category ?? '') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>
                    </div>
                    <div>
                        <label for="sensor_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sensor</label>
                        <select id="sensor_id" name="sensor_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">
                            <option value="">No sensor assigned</option>
                            @foreach($sensors as $sensor)
                                <option value="{{ $sensor->id }}" @selected((int) old('sensor_id', $item->sensor_id ?? 0) === $sensor->id)>{{ $sensor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <label class="inline-flex items-center">
                    <input name="is_active" type="checkbox" class="h-5 w-5 text-primary rounded" @checked(old('is_active', $item->is_active ?? true))>
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                </label>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center justify-center bg-primary hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Update' : 'Create' }} {{ $resourceName }}
                    </button>
                    <a href="{{ route('super-admin.' . $type . '.index') }}" class="inline-flex items-center justify-center bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
