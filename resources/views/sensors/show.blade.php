@extends('layouts.app')

@section('title', $sensor->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm">
            <li><a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('sensors.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary">Sensors</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><span class="text-gray-800 dark:text-white font-semibold">{{ $sensor->name }}</span></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
        <!-- Sensor Image -->
        <div>
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg shadow-lg overflow-hidden h-72 sm:h-80 lg:h-96 flex items-center justify-center">
                @if($sensor->image)
                    <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-microchip text-9xl text-white"></i>
                @endif
            </div>
        </div>

        <!-- Sensor Info -->
        <div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 dark:text-white mb-4 break-words">{{ $sensor->name }}</h1>
            
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-check-circle mr-1"></i> Active
                </span>
                <span class="text-gray-500 dark:text-gray-400">
                    <i class="fas fa-project-diagram mr-1"></i> {{ $sensor->projects()->count() }} Projects
                </span>
                <span class="text-gray-500 dark:text-gray-400">
                    <i class="fas fa-video mr-1"></i> {{ $sensor->videos()->count() }} Videos
                </span>
            </div>

            <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 mb-6">{{ $sensor->description }}</p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Difficulty Level</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">Beginner to Advanced</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Category</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">Electronics</p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#projects" class="flex-1 text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-project-diagram mr-2"></i> View Projects
                </a>
                <a href="#tutorials" class="flex-1 text-center bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-play-circle mr-2"></i> Watch Tutorials
                </a>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    @if($sensor->how_it_works)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
            <i class="fas fa-cog text-primary mr-2"></i> How It Works
        </h2>
        <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">{{ $sensor->how_it_works }}</p>
    </div>
    @endif

    <!-- Use Cases -->
    @if($sensor->use_cases)
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
        <h2 class="text-3xl font-bold mb-6">
            <i class="fas fa-lightbulb mr-2"></i> Use Cases
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(explode(',', $sensor->use_cases) as $useCase)
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur">
                <i class="fas fa-check-circle mr-2"></i>
                {{ trim($useCase) }}
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Components Needed -->
    @if($sensor->components_needed)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
            <i class="fas fa-tools text-primary mr-2"></i> Components Needed
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach(explode(',', $sensor->components_needed) as $component)
            <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <i class="fas fa-microchip text-primary mr-3"></i>
                <span class="text-gray-700 dark:text-gray-300">{{ trim($component) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Projects -->
    @if($relatedProjects->count() > 0)
    <div id="projects" class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
            <i class="fas fa-project-diagram text-primary mr-2"></i> Related Projects
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($relatedProjects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $project->difficulty }}
                    </span>
                    @if($project->is_featured)
                    <span class="text-yellow-500">
                        <i class="fas fa-star"></i> Featured
                    </span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $project->title }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($project->description, 100) }}</p>
                <a href="{{ route('projects.show', $project->slug) }}" class="text-primary font-semibold hover:underline">
                    View Project <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endforeach
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('projects.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                View All Projects
            </a>
        </div>
    </div>
    @endif

    <!-- Related Videos -->
    @if($relatedVideos->count() > 0)
    <div id="tutorials" class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
            <i class="fas fa-video text-red-600 mr-2"></i> Video Tutorials
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedVideos as $video)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                <div class="relative pb-[56.25%]">
                    <iframe 
                        class="absolute inset-0 w-full h-full" 
                        src="https://www.youtube.com/embed/{{ $video->youtube_id }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">{{ $video->title }}</h3>
                    @if($video->description)
                    <p class="text-gray-600 dark:text-gray-300 text-sm">{{ Str::limit($video->description, 80) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('videos.index') }}" class="inline-block bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                View All Tutorials
            </a>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="text-center mt-12">
        <a href="{{ route('sensors.index') }}" class="inline-flex items-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sensors
        </a>
    </div>
</div>
@endsection
