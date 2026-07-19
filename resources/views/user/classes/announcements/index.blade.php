@extends('layouts.app')

@section('title', 'Announcements - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Class
    </a>

    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Announcements</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">{{ $class->name }} · {{ $announcements->total() }} {{ Str::plural('announcement', $announcements->total()) }}</p>

    @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bullhorn text-yellow-600 dark:text-yellow-300 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $announcement->title }}</h2>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $announcement->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                        {{ $announcement->content }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bullhorn text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Announcements</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check back later for updates!</p>
        </div>
    @endif

    @if($announcements->hasPages())
        <div class="mt-6">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection