@extends('layouts.app')

@section('title', 'Sensors')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 dark:text-white mb-4">Explore Sensors</h1>
        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-400">Discover different types of sensors and learn how they work</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search sensors..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="w-full md:w-48">
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    <option value="temperature">Temperature</option>
                    <option value="motion">Motion</option>
                    <option value="gas">Gas</option>
                    <option value="distance">Distance</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Sensors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($sensors as $sensor)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2 flex flex-col">
            <!-- Image Container with Fixed Size -->
            <div class="h-56 w-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden">
                @if($sensor->image)
                    <img src="{{ Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-microchip text-8xl text-white"></i>
                @endif
            </div>
            
            <!-- Content -->
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="text-2xl font-bold mb-3 text-gray-800 dark:text-white">{{ $sensor->name }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4 flex-1">{{ Str::limit($sensor->description, 120) }}</p>
                
                <!-- Quick Info -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-project-diagram mr-1"></i> 
                        {{ $sensor->projects()->count() }} Projects
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-video mr-1"></i> 
                        {{ $sensor->videos()->count() }} Videos
                    </span>
                </div>
                
                <!-- Action Button -->
                <a href="{{ route('sensors.show', $sensor->slug) }}" class="inline-block w-full text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    Learn More <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($sensors->hasPages())
    <div class="flex justify-center">
        {{ $sensors->links() }}
    </div>
    @endif

    <!-- Empty State -->
    @if($sensors->count() === 0)
    <div class="text-center py-16">
        <i class="fas fa-microchip text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Sensors Found</h3>
        <p class="text-gray-500 dark:text-gray-500">Check back later for new sensors!</p>
    </div>
    @endif
</div>
@endsection
