@extends('layouts.app')

@section('title', 'Database Backup')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.dashboard') }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Database Backup</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Download a backup of your database.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-database text-indigo-600 dark:text-indigo-400 text-2xl"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Download Backup</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This will download a complete SQL dump of your database.</p>
        
        <a href="{{ route('super-admin.backup.download') }}" 
           class="inline-flex items-center px-5 py-3 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
            <i class="fas fa-download mr-2"></i> Download Backup (.sql)
        </a>
    </div>
</div>
@endsection