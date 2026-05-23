@extends('layouts.app')

@section('title', 'Manage Suggestions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
            User Suggestions
        </h1>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Suggestions Table --}}
    @if($suggestions->count() > 0)

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                {{-- Table Header --}}
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            User
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Title
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach($suggestions as $suggestion)

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                            {{-- User --}}
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $suggestion->user->name }}
                            </td>

                            {{-- Title --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ Str::limit($suggestion->title, 50) }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($suggestion->status=='pending') bg-yellow-100 text-yellow-800
                                    @elseif($suggestion->status=='reviewed') bg-blue-100 text-blue-800
                                    @elseif($suggestion->status=='implemented') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">

                                    {{ ucfirst($suggestion->status) }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $suggestion->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('admin.suggestions.show', $suggestion) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($suggestions->hasPages())
            <div class="flex justify-center mt-8">
                {{ $suggestions->links() }}
            </div>
        @endif

    @else

        {{-- Empty State --}}
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-lg">

            <i class="fas fa-lightbulb text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>

            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">
                No Suggestions
            </h3>

        </div>

    @endif

</div>
@endsection