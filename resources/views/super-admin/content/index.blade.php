@extends('layouts.app')

@section('title', 'Super Admin ' . $title)

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">
            <div>
                <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Super Admin
                </a>
                <p class="text-sm font-semibold text-primary uppercase">Content Oversight</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mt-1">{{ $title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl">{{ $description }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('super-admin.content.create', $type) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-plus mr-2"></i>Add {{ Str::singular($title) }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-{{ isset($stats['featured']) ? '4' : '3' }} gap-4 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Inactive</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['inactive'] }}</p>
            </div>
            @if(isset($stats['featured']))
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Featured</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['featured'] }}</p>
                </div>
            @endif
        </div>

        @if($items->count() > 0)
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title }} Records</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Latest records are shown first.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                @if($type === 'sensors')
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Image</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">
                                    @if($type === 'sensors')
                                        <td class="px-6 py-4">
                                            @if($item->image)
                                                <img src="{{ Str::startsWith($item->image, ['images/', '/images/']) ? asset($item->image) : asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="h-16 w-16 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                            @else
                                                <img src="{{ asset('images/no-image.svg') }}" alt="No image" class="h-16 w-16 rounded-lg object-cover border border-gray-200 dark:border-gray-700 opacity-60">
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $item->title ?? $item->name }}
                                        </p>
                                        @if(isset($item->description))
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($item->description, 74) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        @if($type === 'projects')
                                            <p>{{ $item->sensor->name ?? 'No sensor assigned' }}</p>
                                            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $item->difficulty }}</p>
                                        @elseif($type === 'products')
                                            <p>{{ $item->category ?? 'Uncategorized' }}</p>
                                            <p class="text-gray-500 dark:text-gray-400 mt-1">₱{{ number_format((float) $item->price, 2) }}</p>
                                        @elseif($type === 'videos')
                                            <p>{{ $item->category ?? 'Uncategorized' }}</p>
                                            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $item->sensor->name ?? 'No sensor assigned' }}</p>
                                        @else
                                            <p>{{ Str::limit($item->use_cases ?? 'Sensor catalog item', 72) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if($item->is_active)
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                                            @else
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200">Inactive</span>
                                            @endif
                                            @if($type === 'projects' && $item->is_featured)
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Featured</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('super-admin.content.edit', [$type, $item->id]) }}" class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-200 dark:hover:bg-blue-900 transition">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                        <form method="POST" action="{{ route('super-admin.content.destroy', [$type, $item->id]) }}" class="inline-block ml-2" onsubmit="return confirm('Delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-950 dark:text-red-200 dark:hover:bg-red-900 transition">
                                                <i class="fas fa-trash mr-2"></i>Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($items->hasPages())
                <div class="flex justify-center mt-8">{{ $items->links() }}</div>
            @endif
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                <i class="fas fa-layer-group text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No {{ $title }} Yet</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Records will appear here once they are added.</p>
                <a href="{{ route('super-admin.content.create', $type) }}" class="inline-flex items-center bg-primary text-white px-5 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-plus mr-2"></i>Add {{ Str::singular($title) }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
