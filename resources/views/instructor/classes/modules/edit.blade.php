@extends('layouts.app')

@section('title', 'Edit Module')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('instructor.classes.modules.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Modules
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Edit Module</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update module for {{ $class->name }}.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('instructor.classes.modules.update', [$class, $module]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($module->file_name)
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex items-center gap-4">
                    <i class="fas fa-file-alt text-2xl text-blue-500"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Current File: {{ $module->file_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Upload a new one to replace it.</p>
                    </div>
                </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Module Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title', $module->title) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Additional Information <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="content" id="content" rows="6"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('content') border-red-500 @enderror"
                            placeholder="Any extra instructions or notes for students...">{{ old('content', $module->content) }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Attachment <span class="text-gray-400 font-normal">(optional, PDF/Word, max 10MB)</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-primary/50 dark:hover:border-primary/50 transition cursor-pointer"
                            x-data="{ fileName: null, fileSize: null, dragging: false }"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false; const file = $event.dataTransfer.files[0]; if(file) { fileName = file.name; fileSize = Math.round(file.size / 1024); document.getElementById('file').files = $event.dataTransfer.files; }"
                            :class="{ 'border-primary bg-primary/5 dark:bg-primary/10': dragging }"
                            onclick="document.getElementById('file').click()">
                            <div class="space-y-1 text-center" x-show="!fileName">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Click to upload new file</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Leave empty to keep current</p>
                            </div>
                            <div class="text-center" x-show="fileName" x-cloak>
                                <i class="fas fa-file-alt text-2xl text-blue-500 mb-1"></i>
                                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="fileName"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="fileSize + ' KB'"></p>
                                <button type="button" 
                                    @click.stop="fileName = null; fileSize = null; document.getElementById('file').value = ''"
                                    class="mt-2 text-xs text-red-500 hover:underline">Remove</button>
                            </div>
                            <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" class="hidden"
                                @change="fileName = $event.target.files[0]?.name; fileSize = Math.round($event.target.files[0]?.size / 1024)">
                        </div>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $module->is_published) ? 'checked' : '' }}
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Publish (visible to students)</label>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Update Module
                    </button>
                    <a href="{{ route('instructor.classes.modules.index', $class) }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection