@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-8">Notifications</h1>

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <a href="{{ $notification->link ?? '#' }}" 
                    onclick="markAsRead({{ $notification->id }})"
                    class="block p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 {{ $notification->is_read ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-bell-slash text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Notifications</h3>
        </div>
    @endif
</div>
@endsection