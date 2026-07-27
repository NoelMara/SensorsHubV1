@extends('layouts.app')

@section('title', 'File Too Large')

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-file-excel text-3xl text-red-500"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">File Too Large</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-6">The file exceeds the maximum size of 10MB.</p>
    <a href="javascript:history.back()" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">Go Back</a>
</div>
@endsection