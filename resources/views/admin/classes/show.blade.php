@extends('layouts.app')

@section('title', $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Classes
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $class->name }}</h1>
                @if($class->section)
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">
                        Block {{ $class->section }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.classes.announcements.index', $class) }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-xs sm:text-sm">
                    <i class="fas fa-bullhorn mr-1"></i> Announcements
                </a>
                <a href="{{ route('admin.classes.modules.index', $class) }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs sm:text-sm">
                    <i class="fas fa-book-open mr-1"></i> Modules
                </a>
                <a href="{{ route('admin.classes.activities.index', $class) }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-xs sm:text-sm">
                    <i class="fas fa-tasks mr-1"></i> Activities
                </a>
                <a href="{{ route('admin.classes.leaderboard', $class) }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-xs sm:text-sm">
                    <i class="fas fa-trophy mr-1"></i> Leaderboard
                </a>
                <a href="{{ route('admin.classes.edit', $class) }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-xs sm:text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST"
                    onsubmit="return confirm('Delete this class? This cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs sm:text-sm">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @if($class->description)
            <p class="text-gray-600 dark:text-gray-400 mt-3">{{ $class->description }}</p>
        @endif
    </div>

    <!-- Class Code Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 mb-8 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Share this code with your students</p>
        <div class="flex items-center justify-center gap-3 sm:gap-4">
            <p class="text-2xl sm:text-5xl font-bold text-primary tracking-[0.3em]">{{ $class->code }}</p>
            <button onclick="navigator.clipboard.writeText('{{ $class->code }}'); this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'; setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i> Copy'},2000)"
                class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs sm:text-sm">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                Students ({{ $class->students->count() }})
            </h2>
        </div>
        @if($class->students->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($class->students as $student)
                    <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $student->name }}</p>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                            @if($student->pivot->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                <form action="{{ route('admin.classes.approve', [$class, $student->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.classes.reject', [$class, $student->id]) }}" method="POST"
                                    onsubmit="return confirm('Remove this student? Their submissions will be kept.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-xs">
                                        <i class="fas fa-user-minus mr-1"></i> Remove
                                    </button>
                                </form>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Approved</span>
                                <form action="{{ route('admin.classes.reject', [$class, $student->id]) }}" method="POST"
                                    onsubmit="return confirm('Remove this student? Their submissions will be kept.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-xs">
                                        <i class="fas fa-user-minus mr-1"></i> Remove
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">No students have joined yet.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Share the class code above!</p>
            </div>
        @endif
    </div>
</div>
@endsection