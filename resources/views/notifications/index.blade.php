@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Notifications</h1>
        @php $unreadCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
        @if($unreadCount > 0)
            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-medium rounded-full">
                {{ $unreadCount }} unread
            </span>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <a href="{{ $notification->link ?? '#' }}" 
                    onclick="markAsRead({{ $notification->id }}, this)"
                    class="notification-item block p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 relative {{ $notification->is_read ? '' : 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-l-blue-500' }}">
                    
                    {{-- Unread Dot --}}
                    @if(!$notification->is_read)
                        <span class="notification-dot absolute top-4 right-4 w-3 h-3 bg-blue-500 rounded-full"></span>
                    @endif

                    <div class="pr-6">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 break-words">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
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