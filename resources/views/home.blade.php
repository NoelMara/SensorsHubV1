@extends('layouts.app')

@section('title', 'Home')

@section('content')
@auth
<!-- Dashboard Content for Authenticated Users -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 sm:p-8 text-white mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold mb-2 break-words">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-blue-100 text-sm sm:text-base">Manage your projects, suggestions, and explore new sensors.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">My Suggestions</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $suggestionsCount }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-lightbulb text-primary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.suggestions') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Saved Projects</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $savedProjectsCount }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-bookmark text-secondary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.saved') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Profile</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white break-all">{{ $user->email }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full shrink-0">
                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="text-primary text-sm hover:underline mt-2 inline-block">Edit profile →</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" onclick="document.getElementById('suggestionModal').classList.remove('hidden')" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-plus-circle text-primary text-2xl shrink-0"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Submit New Suggestion</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Share your project idea</p>
                </div>
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-search text-secondary text-2xl shrink-0"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Browse Projects</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Explore sensor projects</p>
                </div>
            </a>
            <a href="https://sensors-hub-simulator.vercel.app/" target="_blank" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-flask text-orange-600 text-2xl shrink-0"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Simulation</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Test sensor circuits online</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Sensors -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Featured Sensors</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredSensors as $sensor)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-32 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-microchip text-4xl text-white"></i>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold mb-2 text-gray-800 dark:text-white">{{ $sensor->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 flex-1 text-sm">{{ Str::limit($sensor->description, 80) }}</p>
                    <a href="{{ route('sensors.show', $sensor->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                        Learn More <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('sensors.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition text-sm">
                View All Sensors
            </a>
        </div>
    </div>

    <!-- Featured Projects -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Featured Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($featuredProjects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <span class="bg-blue-100 dark:bg-blue-900 text-primary px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $project->difficulty }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-xs">
                            <i class="fas fa-microchip mr-1"></i> {{ $project->sensor?->name ?? 'General' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800 dark:text-white">{{ $project->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 flex-1 text-sm">{{ Str::limit($project->description, 100) }}</p>
                    <a href="{{ route('projects.show', $project->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                        View Project <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('projects.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition text-sm">
                View All Projects
            </a>
        </div>
    </div>

    <!-- Recent Suggestions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Recent Suggestions</h2>
        @if($recentSuggestions->count() > 0)
            <div class="space-y-4">
                @foreach($recentSuggestions as $suggestion)
                <div class="border-l-4 border-primary pl-4 py-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $suggestion->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($suggestion->description, 100) }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full 
                        @if($suggestion->status == 'implemented') bg-green-100 text-green-800
                        @elseif($suggestion->status == 'rejected') bg-red-100 text-red-800
                        @elseif($suggestion->status == 'reviewed') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif
                        {{ ucfirst($suggestion->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No suggestions yet. Submit your first idea!</p>
        @endif
    </div>
</div>

<!-- Suggestion Modal -->
<div id="suggestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-start gap-4 mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Submit Project Suggestion</h2>
                <button onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('dashboard.suggestions.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty</label>
                            <select name="difficulty" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">Select difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type</label>
                            <input type="text" name="sensor_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-600">Submit Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
{{-- ==================== GUEST HOME ==================== --}}

<style>
    @keyframes cursor-blink {
        0%, 49% { opacity: 1; }
        50%, 100% { opacity: 0; }
    }
    .cursor-blink {
        display: inline-block;
        width: 0.55ch;
        animation: cursor-blink 1.1s step-end infinite;
    }
    .terminal-line {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
</style>

{{-- Hero --}}
<section class="relative overflow-hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Left: Copy --}}
            <div class="lg:col-span-7">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/50 border border-blue-100 dark:border-blue-900/50 mb-6">
                    <span class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Sensors Hub</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-[1.1] tracking-tight">
                    Learn sensors.<br>
                    Build <span class="text-primary">real</span> circuits.
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 max-w-xl leading-relaxed">
                    A hands-on workspace for students and makers. Study components, follow real projects, and test circuits in a live simulator.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 mb-10">
                    <a href="{{ route('sensors.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 transition shadow-sm hover:shadow-md">
                        Browse Sensors
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        View Projects
                    </a>
                </div>

                <p class="terminal-line text-sm text-gray-500 dark:text-gray-400">
                    <span class="text-secondary font-semibold">$</span> start with a sensor, build something real<span class="cursor-blink">▌</span>
                </p>
            </div>

            {{-- Right: Code preview --}}
            <div class="lg:col-span-5">
                <div class="relative">
                    <div class="absolute -inset-3 bg-gradient-to-tr from-blue-500/10 via-transparent to-blue-500/5 rounded-2xl blur-xl pointer-events-none"></div>

                    <div class="relative bg-gray-900 dark:bg-black border border-gray-800 rounded-xl overflow-hidden shadow-2xl">
                        {{-- Window bar --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800 bg-gray-950">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500/80"></span>
                            </div>
                            <span class="terminal-line text-[10px] uppercase tracking-widest text-gray-500">main.py — pico</span>
                        </div>

                        {{-- Code --}}
                        <div class="p-5 terminal-line text-xs leading-relaxed space-y-0.5">
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">1</span>
                                <span class="whitespace-pre"><span class="text-pink-400">from</span> <span class="text-gray-300">machine</span> <span class="text-pink-400">import</span> <span class="text-gray-300">ADC, Pin</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">2</span>
                                <span class="whitespace-pre"><span class="text-pink-400">import</span> <span class="text-gray-300">time</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">3</span>
                                <span class="whitespace-pre"> </span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">4</span>
                                <span class="whitespace-pre"><span class="text-gray-300">sensor = ADC(Pin(</span><span class="text-amber-400">26</span><span class="text-gray-300">))</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">5</span>
                                <span class="whitespace-pre"> </span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">6</span>
                                <span class="whitespace-pre"><span class="text-purple-400">while</span> <span class="text-amber-400">True</span><span class="text-gray-300">:</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">7</span>
                                <span class="whitespace-pre"><span class="text-gray-300">    value = sensor.</span><span class="text-blue-400">read_u16</span><span class="text-gray-300">()</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">8</span>
                                <span class="whitespace-pre"><span class="text-gray-300">    </span><span class="text-blue-400">print</span><span class="text-gray-300">(value)</span></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 select-none w-6 text-right mr-4 shrink-0">9</span>
                                <span class="whitespace-pre"><span class="text-gray-300">    time.</span><span class="text-blue-400">sleep</span><span class="text-gray-300">(</span><span class="text-amber-400">1</span><span class="text-gray-300">)</span></span>
                            </div>
                        </div>

                        {{-- Status bar --}}
                        <div class="flex items-center justify-between px-4 py-2 border-t border-gray-800 bg-gray-950 terminal-line text-[10px] uppercase tracking-widest">
                            <span class="text-gray-500">Raspberry Pi Pico</span>
                            <span class="text-secondary flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                Running
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Sensors --}}
<section class="bg-gray-50 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="flex items-end justify-between mb-10 gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-primary mb-2">01 — Sensors</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Explore the components</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Every project starts with a sensor. Start here.</p>
            </div>
            <a href="{{ route('sensors.index') }}" class="hidden sm:inline-flex text-primary text-sm font-semibold hover:underline whitespace-nowrap items-center gap-1">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredSensors as $sensor)
            <a href="{{ route('sensors.show', $sensor->slug) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col group">
                <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 overflow-hidden">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-microchip text-4xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white mb-2 group-hover:text-primary transition line-clamp-2">{{ $sensor->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 flex-1 line-clamp-2 mb-4">{{ Str::limit($sensor->description, 90) }}</p>
                    <span class="text-primary font-semibold text-sm inline-flex items-center gap-1">
                        Learn more <i class="fas fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="sm:hidden text-center mt-8">
            <a href="{{ route('sensors.index') }}" class="text-primary text-sm font-semibold hover:underline">
                View all sensors →
            </a>
        </div>
    </div>
</section>

{{-- Featured Projects --}}
<section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="flex items-end justify-between mb-10 gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-primary mb-2">02 — Projects</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Build these next</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Step-by-step walkthroughs with real components.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="hidden sm:inline-flex text-primary text-sm font-semibold hover:underline whitespace-nowrap items-center gap-1">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($featuredProjects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col group">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        @if($project->difficulty === 'Beginner') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                        @elseif($project->difficulty === 'Intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                        @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                        @endif">
                        {{ $project->difficulty }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-xs inline-flex items-center gap-1">
                        <i class="fas fa-microchip"></i> {{ $project->sensor?->name ?? 'General' }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 group-hover:text-primary transition line-clamp-2">{{ $project->title }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm flex-1 line-clamp-2 mb-4">{{ Str::limit($project->description, 120) }}</p>
                <span class="text-primary font-semibold text-sm inline-flex items-center gap-1">
                    View project <i class="fas fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                </span>
            </a>
            @endforeach
        </div>

        <div class="sm:hidden text-center mt-8">
            <a href="{{ route('projects.index') }}" class="text-primary text-sm font-semibold hover:underline">
                View all projects →
            </a>
        </div>
    </div>
</section>

{{-- Latest Tutorials --}}
@if($latestVideos->count() > 0)
<section class="bg-gray-50 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="flex items-end justify-between mb-10 gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-primary mb-2">03 — Tutorials</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Watch & build</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Follow along, then make it your own.</p>
            </div>
            <a href="{{ route('videos.index') }}" class="hidden sm:inline-flex text-primary text-sm font-semibold hover:underline whitespace-nowrap items-center gap-1">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestVideos as $video)
            <a href="{{ $video->youtube_link ?? route('videos.index') }}" target="_blank" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col group">
                <div class="relative pb-[56.25%] bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    @if($video->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center">
                            <div class="w-11 h-11 rounded-full bg-red-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-play text-white text-sm ml-0.5"></i>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">{{ $video->category ?? 'Tutorial' }}</p>
                    <h3 class="font-semibold text-sm text-gray-800 dark:text-white line-clamp-2 mb-2 group-hover:text-primary transition">{{ $video->title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 flex-1">{{ Str::limit($video->description, 70) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="relative overflow-hidden bg-gray-900 dark:bg-black">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 text-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-400 mb-4">Get started</p>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 tracking-tight">
            Ready to build?
        </h2>
        <p class="text-gray-400 mb-10 max-w-xl mx-auto text-lg">
            Create a free account to submit project ideas, join classes, and track everything you learn.
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">
            Create Free Account
            <i class="fas fa-arrow-right text-sm"></i>
        </a>
        <p class="terminal-line text-sm text-gray-500 mt-10">
            <span class="text-secondary font-semibold">$</span> ready when you are<span class="cursor-blink">▍</span>
        </p>
    </div>
</section>
@endauth
@endsection