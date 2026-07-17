@extends('layouts.app')

@section('title', 'Announcements - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Announcements - {{ $class->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $announcements->total() }} {{ Str::plural('announcement', $announcements->total()) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ route('admin.classes.announcements.create', $class) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i> New Announcement
              </a>
              <a href="{{ route('admin.classes.announcements.import', $class) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap">
                  <i class="fas fa-download mr-1"></i> Import
              </a>
            </div>
        </div>
    </div>

    @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-sm text-yellow-600 dark:text-yellow-300 mt-0.5">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate" title="{{ $announcement->title }}">
                                    {{ Str::limit($announcement->title, 60) }}
                                </h3>
                                @if($announcement->is_published)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 flex-shrink-0">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 flex-shrink-0">Draft</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 line-clamp-2">{{ Str::limit($announcement->content, 150) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $announcement->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('admin.classes.announcements.edit', [$class, $announcement]) }}" 
                               class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Edit Announcement">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.classes.announcements.destroy', [$class, $announcement]) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition"
                                        title="Delete Announcement">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-bullhorn text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Announcements Yet</h3>
            <p class="text-gray-500">Post your first announcement to the class!</p>
        </div>
     @endif

    @if($announcements->hasPages())
        <div class="mt-6">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection