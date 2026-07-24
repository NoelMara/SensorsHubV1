@extends('layouts.app')

@section('title', 'My Suggestions')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">My Suggestions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track the status of your project suggestions</p>
        </div>
        <button onclick="document.getElementById('suggestionModal').classList.remove('hidden')" 
                class="bg-primary text-white px-4 py-2.5 rounded-xl hover:bg-blue-600 transition text-sm font-medium flex-shrink-0 shadow-sm">
            <i class="fas fa-plus mr-1.5"></i> New Suggestion
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Overview</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $suggestions->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $suggestions->where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pending</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $suggestions->where('status', 'reviewed')->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Reviewed</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $suggestions->where('status', 'implemented')->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Implemented</p>
                </div>
            </div>
        </div>
    </div>

    @if($suggestions->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Suggestions ({{ $suggestions->count() }})</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($suggestions as $suggestion)
                    <div class="px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $suggestion->title }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ Str::limit($suggestion->description, 150) }}</p>
                                
                                <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    @if($suggestion->difficulty)
                                        <span><i class="fas fa-signal mr-1"></i>{{ $suggestion->difficulty }}</span>
                                    @endif
                                    @if($suggestion->sensor_type)
                                        <span><i class="fas fa-microchip mr-1"></i>{{ $suggestion->sensor_type }}</span>
                                    @endif
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $suggestion->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <span class="px-2.5 py-1 text-xs font-medium rounded-full flex-shrink-0
                                @if($suggestion->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                                @elseif($suggestion->status == 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                @elseif($suggestion->status == 'implemented') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                @endif">
                                {{ ucfirst($suggestion->status) }}
                            </span>
                        </div>

                        @if($suggestion->admin_notes)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2.5 mt-2">
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-0.5">Administrator Notes:</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $suggestion->admin_notes }}</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-4 mt-2">
                            <a href="{{ route('dashboard.suggestions.show', $suggestion) }}" 
                               class="text-xs text-primary hover:underline font-medium">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            @if($suggestion->status === 'pending')
                                <a href="{{ route('dashboard.suggestions.edit', $suggestion) }}" 
                                   class="text-xs text-blue-600 hover:underline font-medium">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lightbulb text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Suggestions Yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Share your project ideas with the community!</p>
                <button onclick="document.getElementById('suggestionModal').classList.remove('hidden')" 
                        class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                    <i class="fas fa-plus mr-1.5"></i> Submit Your First Suggestion
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Suggestion Modal -->
<div id="suggestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Submit Project Suggestion</h2>
            <button onclick="document.getElementById('suggestionModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.suggestions.store') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Project Title</label>
                        <input type="text" name="title" id="title" required 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror"
                            placeholder="e.g., Automatic Plant Watering System">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="5" required 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('description') border-red-500 @enderror"
                            placeholder="Describe your project idea in detail..."></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Difficulty Level</label>
                            <select name="difficulty" id="difficulty" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                                <option value="">Select difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="sensor_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sensor Type</label>
                            <input type="text" name="sensor_type" id="sensor_type" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                placeholder="e.g., DHT11, HC-SR04">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('suggestionModal').classList.add('hidden')" 
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-paper-plane mr-1.5"></i> Submit Suggestion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection