@extends('layouts.app')

@section('title', 'View Suggestion')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('dashboard.suggestions') }}"
       class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Back to My Suggestions
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        {{-- Author Info --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="text-primary font-bold">{{ strtoupper(substr($suggestion->user?->name ?? '?', 0, 1)) }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $suggestion->user?->name ?? 'Deleted user' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="flex items-start justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $suggestion->title }}</h1>
            <span class="px-3 py-1 text-sm font-semibold rounded-full
                @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                @elseif($suggestion->status === 'implemented') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @endif">
                {{ ucfirst($suggestion->status) }}
            </span>
        </div>

        <div class="space-y-4 mb-6">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                <p class="mt-1 text-gray-800 dark:text-white whitespace-pre-line">{{ $suggestion->description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Difficulty</label>
                    <p class="mt-1 text-gray-800 dark:text-white">{{ $suggestion->difficulty ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Sensor Type</label>
                    <p class="mt-1 text-gray-800 dark:text-white">{{ $suggestion->sensor_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</label>
                    <p class="mt-1 text-gray-800 dark:text-white">{{ $suggestion->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</label>
                    <p class="mt-1 text-gray-800 dark:text-white">{{ $suggestion->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        @if($suggestion->admin_notes)
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Admin Notes:</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $suggestion->admin_notes }}</p>
        </div>
        @endif

        {{-- Comments Section --}}
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                Discussion ({{ $suggestion->comments->count() }})
            </h3>

            @if($suggestion->comments->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($suggestion->comments as $comment)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary font-bold text-sm">{{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $comment->user?->name ?? 'Deleted user' }}</span>
                                    <span class="px-2 py-0.5 text-xs rounded-full
                                        {{ ($comment->user?->role ?? 'user') === 'super_admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                        {{ ($comment->user?->role ?? 'user') === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ ($comment->user?->role ?? 'user') === 'user' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $comment->user?->role ?? 'user')) }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $comment->body }}</p>
                            @if($comment->created_at != $comment->updated_at)
                                <p class="text-xs text-gray-400 mt-2">(edited)</p>
                            @endif

                            @if(auth()->id() === $comment->user_id)
                                <button onclick="document.getElementById('edit-comment-{{ $comment->id }}').classList.toggle('hidden')"
                                    class="mt-2 text-sm text-primary hover:underline">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form id="edit-comment-{{ $comment->id }}" method="POST"
                                    action="{{ route('dashboard.suggestions.comment.update', [$suggestion, $comment]) }}"
                                    class="mt-3 hidden">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="body" rows="3" required
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">{{ $comment->body }}</textarea>
                                    <div class="flex gap-2 mt-2">
                                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 text-sm">Update</button>
                                        <button type="button" onclick="document.getElementById('edit-comment-{{ $comment->id }}').classList.add('hidden')"
                                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">No comments yet. Be the first to share your thoughts!</p>
            @endif

            {{-- Comment Form --}}
            @php
                $userComment = $suggestion->comments->where('user_id', auth()->id())->first();
            @endphp

            @if($userComment)
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    You've already commented.
                    <button onclick="document.getElementById('edit-my-comment').classList.toggle('hidden')"
                        class="text-primary hover:underline">Edit your comment</button>
                </p>
                <form id="edit-my-comment" method="POST"
                    action="{{ route('dashboard.suggestions.comment.update', [$suggestion, $userComment]) }}"
                    class="hidden">
                    @csrf
                    @method('PUT')
                    <textarea name="body" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">{{ $userComment->body }}</textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 text-sm">Update</button>
                        <button type="button" onclick="document.getElementById('edit-my-comment').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('dashboard.suggestions.comment.store', $suggestion) }}">
                    @csrf
                    <textarea name="body" rows="3" required placeholder="Write your comment..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                    <button type="submit" class="mt-2 px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
                        <i class="fas fa-paper-plane mr-2"></i>Post Comment
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection