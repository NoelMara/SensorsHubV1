@extends('layouts.app')

@section('title', 'Database Backup')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.dashboard') }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Database Backup</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create and manage database backups.</p>
    </div>

    {{-- Create Backup --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 text-center mb-6">
        <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-database text-indigo-600 dark:text-indigo-400 text-2xl"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Create New Backup</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This will download a complete SQL dump and save it on the server.</p>
        
        <a href="{{ route('super-admin.backup.download') }}" 
           class="inline-flex items-center px-5 py-3 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
            <i class="fas fa-download mr-2"></i> Download Backup (.sql)
        </a>
    </div>

    {{-- Previous Backups --}}
    @php
        $backupPath = storage_path('app/backups');
        $backups = is_dir($backupPath) ? array_reverse(glob($backupPath . '/*.sql')) : [];
    @endphp

    @if(count($backups) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Previous Backups</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($backups as $backup)
                @php
                    $name = basename($backup);
                    $size = filesize($backup);
                    $date = filemtime($backup);
                @endphp
                <div class="px-5 py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ date('M d, Y h:i A', $date) }} · 
                            {{ $size > 1048576 ? number_format($size / 1048576, 1) . ' MB' : number_format($size / 1024, 1) . ' KB' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <a href="{{ route('super-admin.backup.download-file', $name) }}" 
                           class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="Download">
                            <i class="fas fa-download text-sm"></i>
                        </a>
                        <form action="{{ route('super-admin.backup.delete', $name) }}" method="POST"
                            onsubmit="return confirm('Delete this backup?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection