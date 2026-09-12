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
{{-- ==================== GUEST HOME (workbench redesign) ==================== --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .wb { font-family: 'Inter', system-ui, sans-serif; }
    .wb-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }
    .wb-noise {
        background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.05) 1px, transparent 0);
        background-size: 24px 24px;
    }
    .wb-grid {
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 48px 48px;
    }
    @keyframes wb-blink {
        0%, 49% { opacity: 1; }
        50%, 100% { opacity: 0; }
    }
    .wb-cursor {
        display: inline-block;
        width: 0.55ch;
        height: 1.05em;
        background: #10B981;
        vertical-align: text-bottom;
        animation: wb-blink 1.1s infinite;
        margin-left: 2px;
    }
    @keyframes wb-pulse-dot {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
        50% { opacity: 0.6; box-shadow: 0 0 0 6px rgba(16,185,129,0); }
    }
    .wb-live-dot {
        animation: wb-pulse-dot 2s ease-in-out infinite;
    }
</style>

<div class="wb bg-gray-950 text-white">

{{-- ============ HERO: WORKBENCH ============ --}}
<section class="relative overflow-hidden border-b border-white/5 wb-grid">
    <div class="absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 w-[800px] h-[800px] rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/5 mb-8">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 wb-live-dot"></span>
                    <span class="wb-mono text-[11px] uppercase tracking-widest text-emerald-400">open for learning</span>
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[0.95] tracking-tight mb-8">
                    The workbench<br>
                    for <span class="text-emerald-400">makers</span> who<br>
                    want to <span class="text-amber-400">build</span>.
                </h1>

                <p class="text-lg text-gray-400 max-w-xl mb-10 leading-relaxed">
                    Sensor guides, real project walkthroughs, and a live simulator — all in one place. No fluff. Just components, code, and things that actually work.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('sensors.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-500 text-gray-950 font-bold rounded-md hover:bg-emerald-400 transition group">
                        Start with sensors
                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 border border-white/15 text-white font-bold rounded-md hover:bg-white/5 transition">
                        See projects
                    </a>
                </div>

                <div class="mt-10 flex items-center gap-2 wb-mono text-xs text-gray-500">
                    <span class="text-emerald-400">$</span>
                    <span>ready when you are</span>
                    <span class="wb-cursor"></span>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-br from-emerald-500/20 via-transparent to-amber-500/20 rounded-xl blur-lg"></div>

                    <div class="relative bg-gray-900 border border-white/10 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/5 bg-black/40">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500/70"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/70"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/70"></span>
                            </div>
                            <span class="wb-mono text-[10px] uppercase tracking-widest text-gray-500">live / sensor-01</span>
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <div class="flex items-baseline justify-between mb-2">
                                    <span class="wb-mono text-[10px] uppercase tracking-widest text-gray-500">Temperature</span>
                                    <span class="wb-mono text-[10px] text-emerald-400">● stable</span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="wb-mono text-5xl font-bold text-white tabular-nums">24.5</span>
                                    <span class="wb-mono text-xl text-gray-500">°C</span>
                                </div>
                                <svg class="w-full h-8 mt-2" viewBox="0 0 300 30" preserveAspectRatio="none">
                                    <polyline fill="none" stroke="#10B981" stroke-width="1.5" points="0,20 20,18 40,22 60,15 80,12 100,16 120,10 140,14 160,8 180,12 200,9 220,11 240,7 260,10 280,8 300,6"/>
                                </svg>
                            </div>

                            <div class="border-t border-white/5"></div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Humidity</p>
                                    <p class="wb-mono text-2xl font-bold text-white tabular-nums">62<span class="text-sm text-gray-500">%</span></p>
                                </div>
                                <div>
                                    <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Distance</p>
                                    <p class="wb-mono text-2xl font-bold text-white tabular-nums">142<span class="text-sm text-gray-500">cm</span></p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-white/5 wb-mono text-[10px] uppercase tracking-widest">
                                <span class="text-gray-500">Uptime</span>
                                <span class="text-emerald-400">00:14:23:07</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ NUMBERS STRIP ============ --}}
<section class="border-b border-white/5 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/5">
            <div class="py-8 px-6">
                <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Sensors</p>
                <p class="wb-mono text-4xl font-bold text-white tabular-nums">{{ str_pad($featuredSensors->count(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="py-8 px-6">
                <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Projects</p>
                <p class="wb-mono text-4xl font-bold text-white tabular-nums">{{ str_pad($featuredProjects->count(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="py-8 px-6">
                <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Tutorials</p>
                <p class="wb-mono text-4xl font-bold text-white tabular-nums">{{ str_pad($latestVideos->count(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="py-8 px-6">
                <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Simulator</p>
                <p class="wb-mono text-4xl font-bold text-emerald-400">LIVE</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ SENSORS: COMPONENT DRAWER ============ --}}
<section class="border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex items-end justify-between mb-12 gap-4">
            <div>
                <p class="wb-mono text-[11px] uppercase tracking-widest text-emerald-400 mb-3">— 01 / sensors</p>
                <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight">Browse the drawer</h2>
            </div>
            <a href="{{ route('sensors.index') }}" class="hidden sm:inline-flex items-center gap-2 wb-mono text-xs uppercase tracking-widest text-gray-400 hover:text-emerald-400 transition">
                View all <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-white/5 border border-white/5 rounded-lg overflow-hidden">
            @foreach($featuredSensors as $index => $sensor)
            <a href="{{ route('sensors.show', $sensor->slug) }}" class="group relative bg-gray-950 hover:bg-gray-900 p-6 transition">
                <div class="absolute top-4 right-4 wb-mono text-[10px] text-gray-600">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </div>

                <div class="w-full h-40 mb-5 bg-black/40 border border-white/5 rounded-lg flex items-center justify-center overflow-hidden">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                        <i class="fas fa-microchip text-4xl text-gray-700"></i>
                    @endif
                </div>

                <h3 class="text-lg font-bold mb-2 group-hover:text-emerald-400 transition line-clamp-2">
                    {{ $sensor->name }}
                </h3>

                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ Str::limit($sensor->description, 90) }}</p>

                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <span class="wb-mono text-[10px] uppercase tracking-widest text-gray-600">sensor</span>
                    <i class="fas fa-arrow-right text-xs text-gray-600 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>
            @endforeach
        </div>

        <div class="sm:hidden text-center mt-8">
            <a href="{{ route('sensors.index') }}" class="inline-flex items-center gap-2 wb-mono text-xs uppercase tracking-widest text-gray-400 hover:text-emerald-400 transition">
                View all sensors <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============ PROJECTS: WORKBENCH LIST ============ --}}
<section class="border-b border-white/5 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex items-end justify-between mb-12 gap-4">
            <div>
                <p class="wb-mono text-[11px] uppercase tracking-widest text-amber-400 mb-3">— 02 / projects</p>
                <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight">Build these next</h2>
            </div>
            <a href="{{ route('projects.index') }}" class="hidden sm:inline-flex items-center gap-2 wb-mono text-xs uppercase tracking-widest text-gray-400 hover:text-amber-400 transition">
                View all <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="space-y-px bg-white/5 rounded-lg overflow-hidden border border-white/5">
            @foreach($featuredProjects as $index => $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group block bg-gray-950 hover:bg-gray-900 px-6 py-6 transition">
                <div class="grid grid-cols-12 gap-6 items-center">
                    <div class="col-span-2 sm:col-span-1">
                        <span class="wb-mono text-3xl font-bold text-gray-700 group-hover:text-amber-400 transition tabular-nums">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <div class="col-span-10 sm:col-span-7">
                        <h3 class="text-xl sm:text-2xl font-bold mb-2 group-hover:text-amber-400 transition line-clamp-2">
                            {{ $project->title }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 wb-mono text-[11px] uppercase tracking-widest">
                            <span class="{{ $project->difficulty === 'Beginner' ? 'text-emerald-400' : ($project->difficulty === 'Intermediate' ? 'text-amber-400' : 'text-red-400') }}">
                                ● {{ $project->difficulty }}
                            </span>
                            <span class="text-gray-600">{{ $project->sensor?->name ?? 'GENERAL' }}</span>
                        </div>
                    </div>

                    <div class="hidden sm:block sm:col-span-3">
                        <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($project->description, 100) }}</p>
                    </div>

                    <div class="col-span-12 sm:col-span-1 text-right">
                        <i class="fas fa-arrow-right text-gray-600 group-hover:text-amber-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="sm:hidden text-center mt-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 wb-mono text-xs uppercase tracking-widest text-gray-400 hover:text-amber-400 transition">
                View all projects <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============ TUTORIALS ============ --}}
@if($latestVideos->count() > 0)
<section class="border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex items-end justify-between mb-12 gap-4">
            <div>
                <p class="wb-mono text-[11px] uppercase tracking-widest text-red-400 mb-3">— 03 / tutorials</p>
                <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight">Watch & build</h2>
            </div>
            <a href="{{ route('videos.index') }}" class="hidden sm:inline-flex items-center gap-2 wb-mono text-xs uppercase tracking-widest text-gray-400 hover:text-red-400 transition">
                View all <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($latestVideos as $video)
            <a href="{{ $video->youtube_link ?? route('videos.index') }}" target="_blank" class="group block bg-gray-950 border border-white/5 rounded-lg overflow-hidden hover:border-red-500/50 transition">
                <div class="relative pb-[56.25%] bg-black">
                    @if($video->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 transition" loading="lazy">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-play text-white text-sm ml-0.5"></i>
                            </div>
                        </div>
                        <div class="absolute bottom-2 right-2 px-1.5 py-0.5 bg-black/80 rounded wb-mono text-[10px] text-white">
                            ▶
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <p class="wb-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">{{ $video->category ?? 'TUTORIAL' }}</p>
                    <h3 class="font-bold text-sm mb-2 line-clamp-2 group-hover:text-red-400 transition">{{ $video->title }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ Str::limit($video->description, 70) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ CTA: FINAL ============ --}}
<section class="relative overflow-hidden bg-black wb-grid">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-amber-500/10"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <p class="wb-mono text-[11px] uppercase tracking-widest text-emerald-400 mb-6">— ready to start?</p>

        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
            Plug in.<br>
            <span class="text-emerald-400">Start building.</span>
        </h2>

        <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto">
            Create a free account to submit project ideas, join classes, and track everything you learn.
        </p>

        <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-500 text-gray-950 font-bold rounded-md hover:bg-emerald-400 transition text-lg group">
            Create free account
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>

        <div class="mt-10 flex items-center justify-center gap-2 wb-mono text-xs text-gray-500">
            <span class="text-emerald-400">$</span>
            <span>no credit card needed</span>
            <span class="wb-cursor"></span>
        </div>
    </div>
</section>

</div>
@endauth
@endsection