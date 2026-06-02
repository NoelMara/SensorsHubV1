@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-8">
        <a href="{{ route(($prefix ?? 'admin') . '.products.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Products
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Edit Product</h1>
        <p class="text-gray-600 dark:text-gray-400">Update product details</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ ($prefix ?? 'admin') === 'super-admin' ? route('super-admin.content.update', ['products', $product->id]) : route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Product Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" id="slug" required value="{{ old('slug', $product->slug) }}"
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
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" step="0.01" required value="{{ old('price', $product->price) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <input type="text" name="category" id="category" value="{{ old('category', $product->category) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('category') border-red-500 @enderror">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Link --}}
                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Link
                    </label>
                    <input type="url" name="link" id="link" value="{{ old('link', $product->link) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('link') border-red-500 @enderror">
                    @error('link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Image
                    </label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-20 rounded-lg border border-gray-300 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Current image. Upload a new one to replace.</p>
                        </div>
                    @endif
                    <input type="file" name="image" id="image"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                        Active (visible to users)
                    </label>
                </div>

            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    Update Product
                </button>
               <a href="{{ route(($prefix ?? 'admin') . '.products.index') }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-center">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection