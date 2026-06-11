@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 dark:text-white mb-4">Component Shop</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">Buy sensors and components for your projects</p>
    </div>

    <!-- Filter Options -->
    <form method="GET" action="{{ route('shop.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    <option value="Microcontrollers" {{ request('category') == 'Microcontrollers' ? 'selected' : '' }}>Microcontrollers</option>
                    <option value="Sensor Kits" {{ request('category') == 'Sensor Kits' ? 'selected' : '' }}>Sensor Kits</option>
                    <option value="Components" {{ request('category') == 'Components' ? 'selected' : '' }}>Components</option>
                    <option value="Tools" {{ request('category') == 'Tools' ? 'selected' : '' }}>Tools</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="sort" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
        @foreach($products as $product)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2">
            <!-- Product Image - FIXED VERSION -->
            <div class="h-56 bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center relative">
                @if($product->image)
                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : (Str::startsWith($product->image, ['images/', '/images/']) ? asset($product->image) : asset('storage/' . $product->image)) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-box-open text-7xl text-white"></i>
                @endif

                @if($product->category)
                <span class="absolute top-3 right-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                    {{ $product->category }}
                </span>
                @endif
            </div>
            
                       <!-- Content -->
            <div class="p-6 flex flex-col flex-1">
                <a href="{{ route('shop.show', $product->id) }}">
                    <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white hover:text-primary transition">{{ $product->name }}</h3>
                </a>

                @if($product->description)
                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm flex-1">{{ Str::limit($product->description, 80) }}</p>
                @else
                <div class="flex-1"></div>
                @endif

                @if($product->price)
                <div class="mb-4">
                    <span class="text-3xl font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                </div>
                @else
                <div class="mb-4">
                    <span class="text-lg font-semibold text-gray-500 dark:text-gray-400">Price varies</span>
                </div>
                @endif

                <a href="{{ $product->link }}" 
                   target="_blank" 
                   class="inline-flex items-center justify-center w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 transition shadow-lg mt-auto">
                    <i class="fas fa-shopping-cart mr-2"></i> Buy Now
                </a>
                
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 text-center">
                    <i class="fas fa-external-link-alt mr-1"></i> Reviewed and Recommended
                </p>
            </div>
        </div>
        @endforeach
    </div>

    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->links() }}
    </div>
    @endif

    @if($products->count() === 0)
    <div class="text-center py-16">
        <i class="fas fa-shopping-cart text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Products Found</h3>
        <p class="text-gray-500 dark:text-gray-500">Check back later for new products!</p>
    </div>
    @endif

    <div class="mt-16 bg-gray-50 dark:bg-gray-800 rounded-lg shadow p-8">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-3xl text-primary"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">Link Disclaimer</h3>
                <p class="text-gray-600 dark:text-gray-300">
                   Some links on this page point to external products and shops that we personally reviewed and selected. While we are not officially affiliated with these sellers, we only link to sources we believe are reliable and relevant for your learning. If you have any concerns about a linked product, feel free to contact us.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">Need Help Choosing Components?</h2>
        <p class="text-lg mb-6 text-green-100">Check out our project guides for recommended parts and kits!</p>
        <a href="{{ route('projects.index') }}" class="inline-block bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Browse Projects
        </a>
    </div>
</div>
@endsection