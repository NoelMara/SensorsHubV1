@extends('layouts.app')

@section('title', 'Suggestion Details')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('super-admin.suggestions.index') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Back to Suggestions
            </a>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Suggestion Details</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Review the submission and update its status.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-gray-200 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Suggestion</p>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $suggestion->title }}</h2>
                    </div>
                    <span class="inline-flex self-start px-3 py-1 text-sm font-semibold rounded-full
                        @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($suggestion->status === 'implemented') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ ucfirst($suggestion->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 lg:p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Submitted By</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $suggestion->user?->name ?? 'Deleted user' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $suggestion->user?->email ?? 'No email available' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Submitted</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $suggestion->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $suggestion->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</p>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-5 text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $suggestion->description }}</div>
                </div>

                <form method="POST" action="{{ route('super-admin.suggestions.status', $suggestion) }}" class="pt-6 border-t border-gray-200 dark:border-gray-800">
                    @csrf
                    @method('PUT')
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">
                        <option value="pending" {{ $suggestion->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ $suggestion->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="implemented" {{ $suggestion->status === 'implemented' ? 'selected' : '' }}>Implemented</option>
                        <option value="rejected" {{ $suggestion->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="mt-4 w-full inline-flex items-center justify-center bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </form>
            </div>

            {{-- Comments Section --}}
            <div class="p-6 lg:p-8 border-t border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Discussion</h3>

                @if($suggestion->comments->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($suggestion->comments as $comment)
                            <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $comment->user?->name ?? 'Deleted user' }}</span>
                                        <span class="px-2 py-0.5 text-xs rounded-full
                                           {{ ($comment->user?->role ?? 'user') === 'super_admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : (($comment->user?->role ?? 'user') === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
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
                                        action="{{ route('super-admin.suggestions.comment.update', [$suggestion, $comment]) }}"
                                        class="mt-3 hidden">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="body" rows="3" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">{{ $comment->body }}</textarea>
                                        <div class="flex gap-2 mt-2">
                                            <button type="submit"
                                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 text-sm">
                                                Update
                                            </button>
                                            <button type="button"
                                                onclick="document.getElementById('edit-comment-{{ $comment->id }}').classList.add('hidden')"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">No comments yet.</p>
                @endif

                @php
                    $userComment = $suggestion->comments->where('user_id', auth()->id())->first();
                @endphp

                @if($userComment)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        You've already commented. <button onclick="document.getElementById('edit-my-comment').classList.toggle('hidden')"
                            class="text-primary hover:underline">Edit your comment</button>
                    </p>
                    <form id="edit-my-comment" method="POST"
                        action="{{ route('super-admin.suggestions.comment.update', [$suggestion, $userComment]) }}"
                        class="hidden">
                        @csrf
                        @method('PUT')
                        <textarea name="body" rows="3" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">{{ $userComment->body }}</textarea>
                        <button type="submit"
                            class="mt-2 px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
                            Update Comment
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('super-admin.suggestions.comment.store', $suggestion) }}">
                        @csrf
                        <textarea name="body" rows="3" required placeholder="Write your comment..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"></textarea>
                        <button type="submit"
                            class="mt-2 px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
                            <i class="fas fa-paper-plane mr-2"></i>Post Comment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection