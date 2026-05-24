@extends('layouts.app')

@section('title', 'Manage Sensors')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
                Manage Sensors
            </h1>

            <p class="text-gray-600 dark:text-gray-400">
                Add, edit, or remove sensors from the platform
            </p>
        </div>

        <a href="{{ route('admin.sensors.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">

            <i class="fas fa-plus mr-2"></i>
            Add New Sensor
        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">

            {{ session('success') }}

        </div>
    @endif

    {{-- SENSOR TABLE --}}
    @if($sensors->count() > 0)

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    {{-- TABLE HEADER --}}
                    <thead class="bg-gray-50 dark:bg-gray-700">

                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Image
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>

                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach($sensors as $sensor)

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                                {{-- IMAGE --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $sensor->image ? (Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : asset($sensor->image)) : asset('images/no-image.svg') }}"
                                         alt="{{ $sensor->name }}"
                                         class="h-20 w-20 object-cover rounded-lg shadow">
                                </td>

                                {{-- NAME + DESCRIPTION --}}
                                <td class="px-6 py-4">

                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $sensor->name }}
                                    </div>

                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($sensor->description, 60) }}
                                    </div>

                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 whitespace-nowrap">

                                    @if($sensor->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                    <a href="{{ route('admin.sensors.edit', $sensor) }}"
                                       class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mr-3">

                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.sensors.destroy', $sensor) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this sensor?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:hover:text-red-400">

                                            <i class="fas fa-trash"></i>
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

        {{-- PAGINATION --}}
        @if($sensors->hasPages())

            <div class="flex justify-center mt-8">
                {{ $sensors->links() }}
            </div>

        @endif

    @else

        {{-- EMPTY STATE --}}
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-lg">

            <i class="fas fa-microchip text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>

            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">
                No Sensors
            </h3>

            <p class="text-gray-500 dark:text-gray-500 mb-6">
                Get started by adding your first sensor.
            </p>

            <a href="{{ route('admin.sensors.create') }}"
               class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">

                <i class="fas fa-plus mr-2"></i>
                Add Sensor

            </a>

        </div>

    @endif

</div>
@endsection