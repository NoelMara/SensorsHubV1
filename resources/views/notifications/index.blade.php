@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">

    {{-- Header --}}
    <div class="mb-12">
        <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-3">
            Activity
        </p>
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Notifications
                </h1>
                @php $unreadCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                @if($unreadCount > 0)
                    <span class="text-xs font-medium uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                        ● {{ $unreadCount }} unread
                    </span>
                @endif
            </div>

            {{-- Mark all as read --}}
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white underline underline-offset-4 decoration-gray-300 dark:decoration-gray-700 hover:decoration-gray-900 dark:hover:decoration-white transition">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-2">
            @foreach($notifications as $notification)
                <a href="{{ $notification->link ?? '#' }}" 
                    onclick="markAsRead({{ $notification->id }}, this)"
                    class="notification-item block border border-gray-200 dark:border-gray-800 rounded-lg p-4 hover:border-gray-400 dark:hover:border-gray-600 transition relative group {{ $notification->is_read ? '' : 'bg-gray-50 dark:bg-gray-900/50' }}">

                    {{-- Unread dot --}}
                    @if(!$notification->is_read)
                        <span class="notification-dot absolute top-4 right-4 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    @endif

                    <div class="pr-6">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm group-hover:text-primary transition">
                            {{ $notification->title }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 break-words">
                            {{ $notification->message }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $notifications->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-20 border border-dashed border-gray-200 dark:border-gray-800 rounded-lg">
            <i class="fas fa-bell-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No notifications</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                You're all caught up.
            </p>
        </div>
    @endif
</div>
@endsection