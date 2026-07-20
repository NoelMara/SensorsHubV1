@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-1">My Classes</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Join a class using the code from your instructor.</p>
    </div>

    @php
        $approvedClasses = $classes;
        $pendingClasses = auth()->user()->classes()->wherePivot('status', 'pending')->get();
        $hasClass = $approvedClasses->count() > 0 || $pendingClasses->count() > 0;
    @endphp

    {{-- Join Class Form — only show if not enrolled --}}
    @if(!$hasClass)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Join a Class</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('dashboard.classes.join') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="code" required maxlength="6"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-center text-lg font-bold tracking-[0.3em] uppercase focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                        placeholder="Enter code">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm whitespace-nowrap">
                        <i class="fas fa-plus mr-1.5"></i> Join Class
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Pending Approval --}}
    @if($pendingClasses->count() > 0)
        <div class="mb-8">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Pending Approval</h2>
            <div class="space-y-3">
                @foreach($pendingClasses as $class)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-700 p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $class->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class->instructor->name }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 flex-shrink-0">Waiting</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Enrolled Classes --}}
    @if($approvedClasses->count() > 0)
        <div>
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Enrolled Classes</h2>
            <div class="space-y-3">
                @foreach($approvedClasses as $class)
                    <a href="{{ route('dashboard.classes.show', $class) }}" 
                       class="flex items-center gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:border-primary/30 dark:hover:border-primary/30 hover:shadow-md transition group">
                        
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chalkboard text-blue-600 dark:text-blue-400"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $class->name }}</h3>
                            <div class="flex items-center gap-3 mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                <span><i class="fas fa-user mr-1"></i>{{ $class->instructor->name }}</span>
                                @if($class->section)
                                    <span class="px-1.5 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">Block {{ $class->section }}</span>
                                @endif
                            </div>
                        </div>

                        <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 group-hover:text-primary transition flex-shrink-0"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($pendingClasses->count() === 0 && $approvedClasses->count() === 0)
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-chalkboard text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Classes Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enter a class code above to join your first class!</p>
        </div>
    @endif
</div>
@endsection