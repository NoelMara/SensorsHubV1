@extends('layouts.app')

@section('title', 'My Suggestions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">My Suggestions</h1>
            <p class="text-gray-600 dark:text-gray-400">Track the status of your project suggestions</p>
        </div>
        <button onclick="document.getElementById('suggestionModal').classList.remove('hidden')" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
            <i class="fas fa-plus mr-2"></i> New Suggestion
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $suggestions->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-lightbulb text-primary text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $suggestions->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Reviewed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $suggestions->where('status', 'reviewed')->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-search text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Implemented</p>
                    <p class="text-2xl font-bold text-green-600">{{ $suggestions->where('status', 'implemented')->count() }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggestions List -->
    @if($suggestions->count() > 0)
    <div class="space-y-4">
        @foreach($suggestions as $suggestion)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $suggestion->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($suggestion->description, 150) }}</p>
                    
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                        @if($suggestion->difficulty)
                        <span><i class="fas fa-signal mr-1"></i> {{ $suggestion->difficulty }}</span>
                        @endif
                        @if($suggestion->sensor_type)
                        <span><i class="fas fa-microchip mr-1"></i> {{ $suggestion->sensor_type }}</span>
                        @endif
                        <span><i class="fas fa-calendar mr-1"></i> {{ $suggestion->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="ml-4">
                    @if($suggestion->status == 'pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            <i class="fas fa-clock mr-2"></i> Pending
                        </span>
                    @elseif($suggestion->status == 'reviewed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <i class="fas fa-search mr-2"></i> Reviewed
                        </span>
                    @elseif($suggestion->status == 'implemented')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-check mr-2"></i> Implemented
                        </span>
                    @elseif($suggestion->status == 'rejected')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            <i class="fas fa-times mr-2"></i> Rejected
                        </span>
                    @endif
                </div>
            </div>

            @if($suggestion->admin_notes)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mt-4">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-comment-dots mr-1"></i> Admin Notes:
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $suggestion->admin_notes }}</p>
            </div>
            @endif

            <div class="flex gap-3 mt-4">
                <a href="{{ route('dashboard.suggestions.show', $suggestion) }}"
                   class="text-sm text-primary hover:underline">
                    <i class="fas fa-eye mr-1"></i> View Details
                </a>
                @if($suggestion->status === 'pending')
                    <a href="{{ route('dashboard.suggestions.edit', $suggestion) }}"
                       class="text-sm text-blue-600 hover:underline">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-16 text-center">
        <i class="fas fa-lightbulb text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Suggestions Yet</h3>
        <p class="text-gray-500 dark:text-gray-500 mb-6">Share your project ideas with the community!</p>
        <button onclick="document.getElementById('suggestionModal').classList.remove('hidden')" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
            <i class="fas fa-plus mr-2"></i> Submit Your First Suggestion
        </button>
    </div>
    @endif
</div>

<!-- Suggestion Modal -->
<div id="suggestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Submit Project Suggestion</h2>
                <button onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('dashboard.suggestions.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Title *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                        <textarea name="description" rows="5" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Describe your project idea in detail..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty Level</label>
                            <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type</label>
                            <input type="text" name="sensor_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., DHT11, HC-SR04">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition">Submit Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection