@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <a href="{{ route('shop.index') }}" class="text-primary hover:underline inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Shop
        </a>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Image -->
            <div class="bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-8 min-h-[350px]">
                @if($product->image)
                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : (Str::startsWith($product->image, ['images/', '/images/']) ? asset($product->image) : asset('storage/' . $product->image)) }}" 
                        alt="{{ $product->name }}" 
                        class="max-w-full max-h-80 object-contain rounded-xl">
                @else
                    <div class="text-center">
                        <i class="fas fa-box-open text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-400 dark:text-gray-500 text-sm">No image available</p>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="p-8 flex flex-col">
                @if($product->category)
                <span class="inline-flex items-center gap-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 w-fit">
                    <i class="fas fa-tag text-xs"></i> {{ $product->category }}
                </span>
                @endif

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $product->name }}</h1>

                @if($product->description)
                <div class="prose prose-sm dark:prose-invert mb-6">
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $product->description }}</p>
                </div>
                @endif

                @if($product->price)
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-5 mb-6">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Price</p>
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($product->price, 2) }}</span>
                </div>
                @endif

                <div class="mt-auto space-y-3">
                    <a href="{{ $product->link }}" 
                       target="_blank" 
                       class="inline-flex items-center justify-center w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-md hover:shadow-lg text-base">
                        <i class="fas fa-shopping-cart mr-2"></i> Buy Now
                    </a>
                    <p class="text-xs text-gray-400 dark:text-gray-500 text-center flex items-center justify-center gap-1">
                        <i class="fas fa-external-link-alt"></i> You will be redirected to an external shop
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @php
        $relatedProducts = \App\Models\Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
    @endphp

    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-layer-group text-primary"></i> Similar Products
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
            <a href="{{ route('shop.show', $related->id) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group border border-gray-100 dark:border-gray-700">
                <div class="h-36 bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
                    @if($related->image)
                        <img src="{{ Str::startsWith($related->image, ['http://', 'https://']) ? $related->image : (Str::startsWith($related->image, ['images/', '/images/']) ? asset($related->image) : asset('storage/' . $related->image)) }}" alt="{{ $related->name }}" class="max-w-full max-h-full object-contain">
                    @else
                        <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-600"></i>
                    @endif
                </div>
                <div class="p-4">
                    <p class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-primary transition line-clamp-2">{{ $related->name }}</p>
                    @if($related->price)
                    <p class="text-green-600 dark:text-green-400 font-bold text-sm mt-1">₱{{ number_format($related->price, 2) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection