@extends('layouts.app')

@section('title', 'Suggestion Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('administrator.suggestions.index') }}" class="text-primary hover:underline inline-block text-sm mb-8">
        <i class="fas fa-arrow-left mr-1"></i> Back to Suggestions
    </a>

    {{-- Suggestion Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-3 py-1 text-sm font-medium rounded-full
                @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300
                @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300
                @elseif($suggestion->status === 'implemented') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300
                @else bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300
                @endif">
                {{ ucfirst($suggestion->status) }}
            </span>
            <span class="text-sm text-gray-400">{{ $suggestion->created_at->format('F d, Y') }}</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $suggestion->title }}</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">Submitted by {{ $suggestion->user?->name ?? 'Deleted user' }}</p>

        <div class="text-gray-800 dark:text-gray-200 text-base whitespace-pre-line leading-relaxed">
            {{ $suggestion->description }}
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
            <form method="POST" action="{{ route('administrator.suggestions.status', $suggestion) }}" class="flex items-center gap-3">
                @csrf @method('PUT')
                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Status:</label>
                <select name="status" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                    <option value="pending" {{ $suggestion->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ $suggestion->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="implemented" {{ $suggestion->status === 'implemented' ? 'selected' : '' }}>Implemented</option>
                    <option value="rejected" {{ $suggestion->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">Update Status</button>
            </form>
        </div>
    </div>

    {{-- Discussion --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-8">
            Discussion 
            <span class="text-gray-400 font-normal text-sm ml-1">({{ $suggestion->comments->count() }})</span>
        </h2>

        @if($suggestion->comments->count() > 0)
            <div class="space-y-6 mb-10">
                @foreach($suggestion->comments as $comment)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-sm font-bold text-gray-500 dark:text-gray-400">
                            {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $comment->user?->name ?? 'Deleted user' }}</span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                @if($comment->created_at != $comment->updated_at)
                                    <span class="text-xs text-gray-400">(edited)</span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line leading-relaxed">{{ $comment->body }}</p>
                            @if(auth()->id() === $comment->user_id)
                                <button onclick="document.getElementById('edit-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs text-primary hover:underline mt-2">Edit</button>
                                <form id="edit-{{ $comment->id }}" method="POST"
                                    action="{{ route('administrator.suggestions.comment.update', [$suggestion, $comment]) }}"
                                    class="mt-3 hidden">
                                    @csrf @method('PUT')
                                    <textarea name="body" rows="2" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm mb-2">{{ $comment->body }}</textarea>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm">Save</button>
                                        <button type="button" onclick="document.getElementById('edit-{{ $comment->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-400">Cancel</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @php $userComment = $suggestion->comments->where('user_id', auth()->id())->first(); @endphp

        @if($userComment)
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-sm text-gray-500 dark:text-gray-400">
                You've already commented. 
                <button onclick="document.getElementById('edit-my-comment').classList.toggle('hidden')" class="text-primary hover:underline font-medium">Edit your comment</button>
                <form id="edit-my-comment" method="POST"
                    action="{{ route('administrator.suggestions.comment.update', [$suggestion, $userComment]) }}"
                    class="mt-3 hidden">
                    @csrf @method('PUT')
                    <textarea name="body" rows="2" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm mb-2">{{ $userComment->body }}</textarea>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm">Update Comment</button>
                </form>
            </div>
        @else
            <form method="POST" action="{{ route('administrator.suggestions.comment.store', $suggestion) }}">
                @csrf
                <textarea name="body" rows="3" required placeholder="Write your comment..." class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm mb-3 resize-none"></textarea>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                    <i class="fas fa-paper-plane mr-1"></i> Post Comment
                </button>
            </form>
        @endif
    </div>
</div>
@endsection