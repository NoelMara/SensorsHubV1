@extends('layouts.app')

@section('title', 'Manage Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            {{-- Super Admin Back Button --}}
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('super-admin.dashboard') }}"
                   class="inline-flex items-center text-sm text-primary hover:underline mb-3">

                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Super Admin
                </a>
            @endif

            <p class="text-sm font-semibold text-primary uppercase tracking-wide">
                Project Library
            </p>

            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mt-1">
                Manage Projects
            </h1>

            <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl">
                Review project content, keep tutorials current, and control which builds are visible to learners.
            </p>

        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-3">

            {{-- Logout (Super Admin only) --}}
            @if(auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            @endif

            {{-- Add Project --}}
            <a href="{{ route('admin.projects.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-blue-600 transition shadow-sm">

                <i class="fas fa-plus mr-2"></i>
                Add Project
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">

            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}

        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Projects</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['total'] }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['active'] }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Featured</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['featured'] }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Advanced</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $stats['advanced'] }}
            </p>
        </div>

    </div>

    {{-- Projects Table --}}
    @if($projects->count() > 0)

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Project Records
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Latest projects are shown first.
                </p>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                                Project
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                                Sensor
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                                Difficulty
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                                Visibility
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">
                                Actions
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                                {{-- Project Info --}}
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $project->title }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($project->description, 82) }}
                                    </p>
                                </td>

                                {{-- Sensor --}}
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $project->sensor->name ?? 'No sensor assigned' }}
                                </td>

                                {{-- Difficulty --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($project->difficulty === 'Beginner')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($project->difficulty === 'Intermediate')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else
                                            bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @endif">

                                        {{ $project->difficulty }}
                                    </span>
                                </td>

                                {{-- Visibility --}}
                                <td class="px-6 py-4">

                                    <div class="flex flex-wrap gap-2">

                                        @if($project->is_active)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                                Inactive
                                            </span>
                                        @endif

                                        @if($project->is_featured)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Featured
                                            </span>
                                        @endif

                                    </div>

                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">

                                    <a href="{{ route('admin.projects.edit', $project) }}"
                                       class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-200 dark:hover:bg-blue-900 transition">

                                        <i class="fas fa-edit mr-2"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.projects.destroy', $project) }}"
                                          method="POST"
                                          class="inline-block ml-2"
                                          onsubmit="return confirm('Delete this project?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-950 dark:text-red-200 dark:hover:bg-red-900 transition">

                                            <i class="fas fa-trash mr-2"></i>
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
            <div class="flex justify-center mt-8">
                {{ $projects->links() }}
            </div>
        @endif

    @else

        {{-- Empty State --}}
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">

            <i class="fas fa-project-diagram text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>

            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                No Projects Yet
            </h2>

            <p class="text-gray-500 dark:text-gray-400 mb-6">
                Create the first guided build for your SensorHub learners.
            </p>

            <a href="{{ route('admin.projects.create') }}"
               class="inline-flex items-center bg-primary text-white px-5 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">

                <i class="fas fa-plus mr-2"></i>
                Add Project
            </a>

        </div>

    @endif

</div>
@endsection