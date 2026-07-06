@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm">
            <li><a href="{{ route('home') }}" class="text-primary hover:underline">Home</a></li>
            <li class="text-gray-500 dark:text-gray-400">/</li>
            <li><a href="{{ route('projects.index') }}" class="text-primary hover:underline">Projects</a></li>
            <li class="text-gray-500 dark:text-gray-400">/</li>
            <li class="text-gray-700 dark:text-gray-300">{{ $project->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="p-5 sm:p-8 border-b dark:border-gray-700">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($project->difficulty == 'Beginner') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($project->difficulty == 'Intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            <i class="fas fa-signal mr-1"></i> {{ $project->difficulty }}
                        </span>
                        @if($project->is_featured)
                        <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-star mr-1"></i> Featured Project
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-4 break-words">{{ $project->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                        <span>
                            <i class="fas fa-microchip mr-1 text-primary"></i> 
                            <a href="{{ route('sensors.show', $project->sensor?->slug ?? '#') }}" class="text-primary hover:underline">
                                {{ $project->sensor?->name ?? 'General' }}
                            </a>
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="p-5 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-info-circle mr-2 text-primary"></i>Project Overview
                    </h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
                    </div>
                </div>

                <!-- Instructions -->
                @if($project->instructions)
                <div class="p-5 sm:p-8 border-t dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-tasks mr-2 text-primary"></i>Instructions
                    </h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $project->instructions }}</div>
                    </div>
                </div>
                @endif

                <!-- Code -->
                @if($project->code)
                <div class="p-5 sm:p-8 border-t dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            <i class="fas fa-code mr-2 text-green-500"></i>Code
                        </h2>
                        <button onclick="let btn=this; navigator.clipboard.writeText(document.getElementById('projectCode').textContent); btn.innerHTML='<i class=\'fas fa-check\'></i> Copied!'; btn.classList.add('bg-green-600','hover:bg-green-500'); setTimeout(()=>{btn.innerHTML='<i class=\'fas fa-copy\'></i> Copy Code'; btn.classList.remove('bg-green-600','hover:bg-green-500')},2000)" 
                                class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg transition">
                            <i class="fas fa-copy"></i> Copy Code
                        </button>
                    </div>
                    <div class="relative">
                        <pre id="projectCode" class="bg-gray-950 text-green-400 p-5 rounded-xl text-sm overflow-x-auto border border-gray-700"><code>{{ $project->code }}</code></pre>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Components Needed -->
            @if($project->components_needed)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-tools mr-2 text-primary"></i>Components Needed
                </h3>
                <div class="text-gray-700 dark:text-gray-300">
                    <p class="whitespace-pre-line">{{ $project->components_needed }}</p>
                </div>
            </div>
            @endif

            <!-- Sensor Info -->
            @if($project->sensor)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-microchip mr-2 text-primary"></i>Related Sensor
                </h3>
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-microchip text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-white">{{ $project->sensor->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($project->sensor->description, 60) }}</p>
                    </div>
                </div>
                <a href="{{ route('sensors.show', $project->sensor->slug) }}" class="block text-center bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-600 transition">
                    Learn More <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-bolt mr-2 text-primary"></i>Actions
                </h3>
                
                @auth
                    <form action="{{ route('dashboard.projects.save', $project) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="w-full {{ $isSaved ? 'bg-green-500 hover:bg-green-600' : 'bg-primary hover:bg-blue-600' }} text-white px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas {{ $isSaved ? 'fa-check' : 'fa-bookmark' }} mr-2"></i>
                            {{ $isSaved ? 'Saved' : 'Save Project' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition mb-4">
                        <i class="fas fa-bookmark mr-2"></i>Save Project
                    </a>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Login to save projects</p>
                @endauth

                <a href="{{ route('projects.index') }}" class="block w-full text-center border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-semibold hover:border-primary hover:text-primary transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>
            </div>
        </div>
    </div>

    <!-- Related Projects -->
    @if($project->sensor_id)
        @php
            $relatedProjects = \App\Models\Project::where('sensor_id', $project->sensor_id)
                ->where('id', '!=', $project->id)
                ->where('is_active', true)
                ->limit(3)
                ->get();
        @endphp
        
        @if($relatedProjects->count() > 0)
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Related Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedProjects as $relatedProject)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="p-6">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($relatedProject->difficulty == 'Beginner') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($relatedProject->difficulty == 'Intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ $relatedProject->difficulty }}
                        </span>
                        <h3 class="text-xl font-bold mt-3 mb-2 text-gray-800 dark:text-white">{{ $relatedProject->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">{{ Str::limit($relatedProject->description, 100) }}</p>
                        <a href="{{ route('projects.show', $relatedProject->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                            View Project <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>
@endsection