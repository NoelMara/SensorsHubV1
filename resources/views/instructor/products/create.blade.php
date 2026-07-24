@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route(($prefix ?? 'instructor') . '.products.index') }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Products
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Add New Product</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new product listing.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ ($prefix ?? 'instructor') === 'super-admin' ? route('administrator.content.store', 'products') : route('instructor.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Price</label>
                        <input type="number" name="price" id="price" step="0.01" required value="{{ old('price') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Category</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('category') border-red-500 @enderror">
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product Link</label>
                        <input type="url" name="link" id="link" value="{{ old('link') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('link') border-red-500 @enderror">
                        @error('link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-primary/50 dark:hover:border-primary/50 transition cursor-pointer relative"
                            onclick="document.getElementById('image').click()"
                            x-data="{ preview: null }">
                            <div class="space-y-1 text-center" x-show="!preview">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Click to upload</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                            <div x-show="preview" class="relative">
                                <img :src="preview" class="max-h-32 rounded-lg object-cover">
                                <button type="button" 
                                    @click.stop="preview = null; document.getElementById('image').value = ''"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition shadow">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="file" name="image" id="image" accept="image/*" class="hidden"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                        </div>
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Active (visible to users)</label>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Create Product
                    </button>
                    <a href="{{ route(($prefix ?? 'instructor') . '.products.index') }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection