@extends('layouts.app')

@section('title', $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.classes.index') }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Classes
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $class->name }}</h1>
                @if($class->section)
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm font-semibold">
                        Block {{ $class->section }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.classes.announcements.index', $class) }}" class="px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-xs font-medium">
                    <i class="fas fa-bullhorn mr-1"></i> Announcements
                </a>
                <a href="{{ route('admin.classes.modules.index', $class) }}" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs font-medium">
                    <i class="fas fa-book-open mr-1"></i> Modules
                </a>
                <a href="{{ route('admin.classes.activities.index', $class) }}" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-xs font-medium">
                    <i class="fas fa-tasks mr-1"></i> Activities
                </a>
                <a href="{{ route('admin.classes.leaderboard', $class) }}" class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-xs font-medium">
                    <i class="fas fa-trophy mr-1"></i> Leaderboard
                </a>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <a href="{{ route('admin.classes.edit', $class) }}" class="px-3 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-xs font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST"
                    onsubmit="return confirm('Delete this class? This cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs font-medium">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @if($class->description)
            <p class="text-gray-500 dark:text-gray-400 mt-3 text-sm">{{ $class->description }}</p>
        @endif
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        @php
            $approved = $class->students->where('pivot.status', 'approved')->count();
            $pending = $class->students->where('pivot.status', 'pending')->count();
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $approved }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Approved</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pending }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pending</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $class->modules()->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Modules</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $class->activities()->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Activities</p>
        </div>
    </div>

    {{-- Class Code --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i class="fas fa-key text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Class Code</p>
                <p class="text-xl font-bold text-primary dark:text-blue-400 tracking-[0.2em]">{{ $class->code }}</p>
            </div>
        </div>
        <button onclick="navigator.clipboard.writeText('{{ $class->code }}'); this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'; setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i>'},2000)"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs font-medium">
            <i class="fas fa-copy mr-1"></i> Copy
        </button>
    </div>

    {{-- Students List --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Students ({{ $class->students->count() }})</h2>
                @if($pending > 0)
                    <form action="{{ route('admin.classes.approve-all', $class) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs font-medium">
                            <i class="fas fa-check-double mr-1"></i> Approve All ({{ $pending }})
                        </button>
                    </form>
                @endif
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="studentSearch" placeholder="Search students..." 
                    class="w-full pl-9 pr-9 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                <button type="button" id="clearSearch" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
        @if($class->students->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-gray-700" id="studentList">
                @foreach($class->students as $student)
                    @php
                        $submissions = $student->submissions()
                            ->whereIn('activity_id', $class->activities()->pluck('id'))
                            ->get();
                        $totalPoints = $submissions->sum('score');
                        $submittedCount = $submissions->whereNotNull('submitted_at')->count();
                        $totalActivities = $class->activities()->count();
                    @endphp
                    <div class="student-row px-5 py-3 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition" data-name="{{ strtolower($student->name) }}">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $student->name }}</p>
                            </div>
                        </div>

                        @if($student->pivot->status === 'approved' && $totalActivities > 0)
                            <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-green-500 dark:bg-green-400 h-2 rounded-full" style="width: {{ ($submittedCount / $totalActivities) * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $submittedCount }}/{{ $totalActivities }}</span>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300"><i class="fas fa-star text-yellow-500 mr-1"></i>{{ $totalPoints }} pts</span>
                            </div>
                        @elseif($student->pivot->status === 'approved')
                            <span class="hidden sm:block text-xs text-gray-400 dark:text-gray-500">No activities yet</span>
                        @endif

                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($student->pivot->status === 'pending')
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">Pending</span>
                                <form action="{{ route('admin.classes.approve', [$class, $student->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @else
                                <span class="hidden sm:inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Approved</span>
                            @endif
                            <form action="{{ route('admin.classes.reject', [$class, $student->id]) }}" method="POST"
                                onsubmit="return confirm('Remove this student?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Remove">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="noResults" class="hidden py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Students Found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No students match your search.</p>
            </div>
        @else
            <div class="py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Students Yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Share the class code above to get started!</p>
            </div>
        @endif
    </div>
</div>

{{-- Search Script --}}
<script>
    var searchInput = document.getElementById('studentSearch');
    var clearBtn = document.getElementById('clearSearch');
    
    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase();
        var found = false;
        
        clearBtn.classList.toggle('hidden', query === '');
        
        document.querySelectorAll('.student-row').forEach(function(row) {
            var name = row.getAttribute('data-name');
            var match = name.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) found = true;
        });
        
        var noResults = document.getElementById('noResults');
        var studentList = document.getElementById('studentList');
        if (!found && query !== '') {
            noResults.classList.remove('hidden');
            studentList.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            studentList.classList.remove('hidden');
        }
    });
    
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });
</script>
@endsection