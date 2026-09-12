@extends('layouts.app')

@section('title', 'Home')

@section('content')
@auth
{{-- ==================== AUTHENTICATED HOME (unchanged) ==================== --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 sm:p-8 text-white mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold mb-2 break-words">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-blue-100 text-sm sm:text-base">Manage your projects, suggestions, and explore new sensors.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">My Suggestions</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $suggestionsCount }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl">
                    <i class="fas fa-lightbulb text-primary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.suggestions') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Saved Projects</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $savedProjectsCount }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                    <i class="fas fa-bookmark text-secondary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.saved') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Profile</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white break-all">{{ $user->email }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-xl shrink-0">
                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="text-primary text-sm hover:underline mt-2 inline-block">Edit profile →</a>
        </div>
    </div>
</div>
@else
{{-- ==================== GUEST HOME (redesigned) ==================== --}}

{{-- Hero Section --}}
<section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-cyan-300 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur border border-white/20 rounded-full text-sm font-medium mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Learn · Build · Share
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Master Sensors &<br class="hidden sm:block"> Build Real Projects
            </h1>
            <p class="text-lg sm:text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                Explore 100+ sensor guides, hands-on projects, and interactive tutorials. Perfect for beginners and makers.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('sensors.index') }}" class="px-8 py-3.5 bg-white text-blue-700 rounded-xl font-semibold hover:bg-blue-50 transition shadow-lg text-center">
                    <i class="fas fa-microchip mr-2"></i> Explore Sensors
                </a>
                <a href="{{ route('projects.index') }}" class="px-8 py-3.5 bg-white/10 backdrop-blur border-2 border-white/30 text-white rounded-xl font-semibold hover:bg-white/20 transition text-center">
                    <i class="fas fa-rocket mr-2"></i> View Projects
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4 text-center">
                <i class="fas fa-microchip text-2xl text-blue-200 mb-2"></i>
                <p class="text-2xl font-bold">{{ $featuredSensors->count() }}+</p>
                <p class="text-xs text-blue-200 mt-1">Sensors</p>
            </div>
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4 text-center">
                <i class="fas fa-project-diagram text-2xl text-green-200 mb-2"></i>
                <p class="text-2xl font-bold">{{ $featuredProjects->count() }}+</p>
                <p class="text-xs text-blue-200 mt-1">Projects</p>
            </div>
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4 text-center">
                <i class="fas fa-play-circle text-2xl text-red-200 mb-2"></i>
                <p class="text-2xl font-bold">{{ $latestVideos->count() }}+</p>
                <p class="text-xs text-blue-200 mt-1">Tutorials</p>
            </div>
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-xl p-4 text-center">
                <i class="fas fa-flask text-2xl text-orange-200 mb-2"></i>
                <p class="text-2xl font-bold">24/7</p>
                <p class="text-xs text-blue-200 mt-1">Simulator</p>
            </div>
        </div>
    </div>
</section>

{{-- Quick Access Cards --}}
<section class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-3">What Do You Want to Explore?</h2>
            <p class="text-gray-500 dark:text-gray-400">Pick a topic to get started</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('sensors.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6 text-center hover:scale-105 transition shadow-lg">
                <i class="fas fa-microchip text-4xl mb-3"></i>
                <p class="font-bold">Sensors</p>
                <p class="text-xs text-blue-100 mt-1">Learn components</p>
            </a>
            <a href="{{ route('projects.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6 text-center hover:scale-105 transition shadow-lg">
                <i class="fas fa-project-diagram text-4xl mb-3"></i>
                <p class="font-bold">Projects</p>
                <p class="text-xs text-green-100 mt-1">Build from scratch</p>
            </a>
            <a href="{{ route('videos.index') }}" class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-2xl p-6 text-center hover:scale-105 transition shadow-lg">
                <i class="fas fa-play-circle text-4xl mb-3"></i>
                <p class="font-bold">Tutorials</p>
                <p class="text-xs text-red-100 mt-1">Watch & learn</p>
            </a>
            <a href="https://sensors-hub-simulator.vercel.app/" target="_blank" class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl p-6 text-center hover:scale-105 transition shadow-lg">
                <i class="fas fa-flask text-4xl mb-3"></i>
                <p class="font-bold">Simulation</p>
                <p class="text-xs text-orange-100 mt-1">Test circuits</p>
            </a>
        </div>
    </div>
</section>

{{-- Featured Sensors --}}
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-2">Featured Sensors</h2>
                <p class="text-gray-500 dark:text-gray-400">Handpicked components to start your journey</p>
            </div>
            <a href="{{ route('sensors.index') }}" class="text-primary font-semibold hover:underline text-sm whitespace-nowrap">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredSensors as $sensor)
            <a href="{{ route('sensors.show', $sensor->slug) }}" class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col">
                <div class="h-44 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    @else
                        <i class="fas fa-microchip text-6xl text-white"></i>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold mb-2 text-gray-800 dark:text-white group-hover:text-primary transition line-clamp-2">{{ $sensor->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4 flex-1 text-sm line-clamp-3">{{ $sensor->description }}</p>
                    <span class="text-primary font-semibold text-sm inline-flex items-center">
                        Learn More <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Projects --}}
<section class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-2">Featured Projects</h2>
                <p class="text-gray-500 dark:text-gray-400">Step-by-step guides to build real things</p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-primary font-semibold hover:underline text-sm whitespace-nowrap">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($featuredProjects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group bg-gray-50 dark:bg-gray-700 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($project->difficulty === 'Beginner') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                        @elseif($project->difficulty === 'Intermediate') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                        @endif">
                        {{ $project->difficulty }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-xs inline-flex items-center">
                        <i class="fas fa-microchip mr-1"></i> {{ $project->sensor?->name ?? 'General' }}
                    </span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white group-hover:text-primary transition line-clamp-2">{{ $project->title }}</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4 flex-1 text-sm line-clamp-3">{{ $project->description }}</p>
                <span class="text-primary font-semibold text-sm inline-flex items-center">
                    View Project <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Latest Tutorials --}}
@if($latestVideos->count() > 0)
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-2">Latest Tutorials</h2>
                <p class="text-gray-500 dark:text-gray-400">Watch and code along</p>
            </div>
            <a href="{{ route('videos.index') }}" class="text-primary font-semibold hover:underline text-sm whitespace-nowrap">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestVideos as $video)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all group">
                <div class="relative pb-[56.25%] bg-gray-200 dark:bg-gray-700">
                    @if($video->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg">
                                <i class="fas fa-play text-white ml-0.5"></i>
                            </div>
                        </div>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-play-circle text-4xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-sm mb-2 text-gray-800 dark:text-white line-clamp-2">{{ $video->title }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs line-clamp-2">{{ $video->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-br from-emerald-500 to-teal-600 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-cyan-300 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-lightbulb text-3xl"></i>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Have a Project Idea?</h2>
        <p class="text-lg sm:text-xl mb-8 text-emerald-50 max-w-2xl mx-auto">
            Share your sensor project suggestions with our community and help others learn!
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-700 rounded-xl font-semibold hover:bg-emerald-50 transition shadow-lg text-lg">
            <i class="fas fa-user-plus"></i> Join Now — It's Free
        </a>
    </div>
</section>
@endauth
@endsection