@extends('layouts.app')

@section('title', 'Video Tutorials')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 dark:text-white mb-4">Video Tutorials</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">Learn from YouTube tutorials</p>
    </div>

    <!-- Filter Options -->
    <<form method="GET" action="{{ route('videos.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search videos..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex gap-2">
                <select name="category" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    <option value="Tutorial" {{ request('category') == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                    <option value="Project" {{ request('category') == 'Project' ? 'selected' : '' }}>Project</option>
                    <option value="Review" {{ request('category') == 'Review' ? 'selected' : '' }}>Review</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Videos Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($videos as $video)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2 flex flex-col">
            <!-- YouTube Embed -->
            <div class="relative pb-[56.25%] bg-gray-900">
                <iframe 
                    class="absolute inset-0 w-full h-full" 
                    src="https://www.youtube.com/embed/{{ $video->youtube_id }}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            
            <!-- Content -->
            <div class="p-6 flex-1 flex flex-col">
                <!-- Category Badge -->
                @if($video->category)
                <span class="inline-block bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                    {{ $video->category }}
                </span>
                @endif

                <!-- Title -->
                <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">{{ $video->title }}</h3>

                <!-- Sensor Association -->
                @if($video->sensor)
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    <i class="fas fa-microchip mr-1 text-primary"></i> 
                    {{ $video->sensor->name }}
                </p>
                @endif

                <!-- Description -->
                @if($video->description)
                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm flex-1">{{ Str::limit($video->description, 100) }}</p>
                @endif

                <!-- Action Button -->
                <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" 
                   target="_blank" 
                   class="inline-flex items-center justify-center w-full bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fab fa-youtube mr-2"></i> Watch on YouTube
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($videos->hasPages())
    <div class="flex justify-center">
        {{ $videos->links() }}
    </div>
    @endif

    <!-- Empty State -->
    @if($videos->count() === 0)
    <div class="text-center py-16">
        <i class="fab fa-youtube text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Videos Found</h3>
        <p class="text-gray-500 dark:text-gray-500">Check back later for new tutorials!</p>
    </div>
    @endif

    <!-- Call to Action -->
    <div class="mt-16 bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-8 text-white text-center">
        <h2 class="text-3xl font-bold mb-4"><i class="fab fa-youtube mr-2"></i>Subscribe to Our Channel</h2>
        <p class="text-lg mb-6 text-red-100">Get the latest sensor tutorials and project guides!</p>
        <a href="https://youtube.com" target="_blank" class="inline-block bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Visit YouTube Channel
        </a>
    </div>
</div>
@endsection
