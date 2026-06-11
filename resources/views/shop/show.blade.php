@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <a href="{{ route('shop.index') }}" class="text-primary hover:underline inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Shop
        </a>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Image -->
            <div class="h-64 md:h-full min-h-[300px] bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : (Str::startsWith($product->image, ['images/', '/images/']) ? asset($product->image) : asset('storage/' . $product->image)) }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover">
                @else
                    <i class="fas fa-box-open text-8xl text-gray-300 dark:text-gray-500"></i>
                @endif
            </div>

            <!-- Details -->
            <div class="p-8 flex flex-col">
                @if($product->category)
                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-xs font-semibold mb-3 w-fit">
                    {{ $product->category }}
                </span>
                @endif

                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">{{ $product->name }}</h1>

                @if($product->description)
                <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">{{ $product->description }}</p>
                @endif

                @if($product->price)
                <div class="mb-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Price</p>
                    <span class="text-4xl font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                </div>
                @endif

                <div class="mt-auto space-y-3">
                    <a href="{{ $product->link }}" 
                       target="_blank" 
                       class="inline-flex items-center justify-center w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-lg text-lg">
                        <i class="fas fa-shopping-cart mr-2"></i> Buy Now
                    </a>
                    <p class="text-xs text-gray-400 dark:text-gray-500 text-center">
                        <i class="fas fa-external-link-alt mr-1"></i> You will be redirected to an external shop
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
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Similar Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
            <a href="{{ route('shop.show', $related->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition overflow-hidden group">
                <div class="h-32 bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                    @if($related->image)
                        <img src="{{ Str::startsWith($related->image, ['http://', 'https://']) ? $related->image : (Str::startsWith($related->image, ['images/', '/images/']) ? asset($related->image) : asset('storage/' . $related->image)) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-500"></i>
                    @endif
                </div>
                <div class="p-3">
                    <p class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-primary transition">{{ $related->name }}</p>
                    @if($related->price)
                    <p class="text-green-600 font-bold text-sm mt-1">₱{{ number_format($related->price, 2) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection