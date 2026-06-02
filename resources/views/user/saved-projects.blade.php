@extends('layouts.app')

@section('title', 'Saved Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">My Saved Projects</h1>
        <p class="text-gray-600 dark:text-gray-400">Projects you've bookmarked for later.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Saved Projects Grid -->
    @if($savedProjects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($savedProjects as $saved)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <!-- Project Image -->
            <div class="h-48 bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                @if($saved->project->image)
                   <img src="{{ Str::startsWith($saved->project->image, ['http://', 'https://']) ? $saved->project->image : asset($saved->project->image) }}" alt="{{ $saved->project->title }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-project-diagram text-6xl text-white"></i>
                @endif
            </div>

            <!-- Project Info -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($saved->project->difficulty == 'Beginner') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($saved->project->difficulty == 'Intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ $saved->project->difficulty }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar mr-1"></i>{{ $saved->created_at->format('M d, Y') }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $saved->project->title }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ Str::limit($saved->project->description, 100) }}</p>

                @if($saved->project->sensor)
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <i class="fas fa-microchip mr-2"></i>
                    <span>{{ $saved->project->sensor->name }}</span>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('projects.show', $saved->project->slug) }}" 
                       class="flex-1 bg-primary hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <form action="{{ route('dashboard.projects.save', $saved->project->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                                title="Remove from saved">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
        <div class="mb-6">
            <i class="fas fa-bookmark text-8xl text-gray-300 dark:text-gray-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">No Saved Projects Yet</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Start exploring and save projects you're interested in!</p>
        <a href="{{ route('projects.index') }}" 
           class="inline-block bg-primary hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
            <i class="fas fa-search mr-2"></i>Browse Projects
        </a>
    </div>
    @endif

    <!-- Back to Dashboard -->
    <div class="mt-8">
        <a href="{{ route('dashboard.index') }}" class="inline-flex items-center text-primary hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
