@extends('layouts.app')

@section('title', 'Announcements - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.classes.announcements.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
            </a>
        @else
            <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Class
            </a>
        @endif
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Announcements</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }}</p>
    </div>

    @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-300">
                            <i class="fas fa-bullhorn text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $announcement->title }}</h2>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $announcement->content }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">{{ $announcement->created_at->format('M d, Y h:i A') }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-bullhorn text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Announcements</h3>
            <p class="text-gray-500">Check back later for updates!</p>
        </div>
    @endif

    @if($announcements->hasPages())
        <div class="mt-6">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection