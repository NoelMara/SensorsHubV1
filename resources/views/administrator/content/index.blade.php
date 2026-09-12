@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $title }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
        </div>
        <a href="{{ route('administrator.content.create', $type) }}" class="px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium flex-shrink-0">
            <i class="fas fa-plus mr-1.5"></i> Add {{ Str::singular($title) }}
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('administrator.' . $type . '.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search {{ strtolower($title) }}..."
                class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium">
            <i class="fas fa-search mr-1.5"></i> Search
        </button>
        @if(request('search'))
            <a href="{{ route('administrator.' . $type . '.index') }}" class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium text-center">
                <i class="fas fa-times mr-1"></i> Clear
            </a>
        @endif
    </form>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-{{ isset($stats['featured']) ? '4' : '3' }} gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-layer-group text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Active</p>
                <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-minus-circle text-gray-400 dark:text-gray-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Inactive</p>
                <p class="text-xl font-bold text-gray-400 dark:text-gray-500">{{ $stats['inactive'] }}</p>
            </div>
        </div>
        @if(isset($stats['featured']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Featured</p>
                    <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['featured'] }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Table --}}
    @if($items->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            @if($type === 'sensors')
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Image</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Details</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                @if($type === 'sensors')
                                    <td class="px-6 py-4">
                                        @if($item->image)
                                            <img src="{{ Str::startsWith($item->image, ['http://', 'https://']) ? $item->image : asset($item->image) }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <i class="fas fa-microchip text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title ?? $item->name }}</p>
                                    @if(isset($item->description))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($item->description, 80) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if($type === 'projects')
                                        <p>{{ $item->sensor?->name ?? '—' }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $item->difficulty }}</span>
                                    @elseif($type === 'products')
                                        <p class="font-semibold text-gray-700 dark:text-gray-300">₱{{ number_format((float) $item->price, 2) }}</p>
                                        <p class="text-xs mt-0.5">{{ $item->category ?? '—' }}</p>
                                    @elseif($type === 'videos')
                                        <p>{{ $item->category ?? '—' }}</p>
                                    @else
                                        <p>{{ Str::limit($item->use_cases ?? '', 60) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @if($item->is_active)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                        @endif
                                        @if($type === 'projects' && $item->is_featured)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('administrator.content.edit', [$type, $item->id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-primary bg-primary/10 hover:bg-primary/20 dark:hover:bg-primary/20 transition mr-1">
                                        <i class="fas fa-pen mr-1"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('administrator.content.destroy', [$type, $item->id]) }}" class="inline-block" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 transition">
                                            <i class="fas fa-trash mr-1"></i> Delete
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
            <div class="mt-6">{{ $items->links() }}</div>
        @endif
    @else
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-2xl text-gray-400"></i>
            </div>
            @if(request('search'))
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Results Found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try a different search term.</p>
                <a href="{{ route('administrator.' . $type . '.index') }}" class="inline-block mt-4 text-primary hover:underline text-sm font-medium">
                    <i class="fas fa-times mr-1"></i> Clear search
                </a>
            @else
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No {{ $title }} yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your first {{ Str::singular($title) }} to get started.</p>
            @endif
        </div>
    @endif
</div>
@endsection