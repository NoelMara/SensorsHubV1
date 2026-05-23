@extends('layouts.app')

@section('title', 'Add ' . $section)

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="mb-8">

        <a href="{{ url()->previous() }}"
           class="text-primary hover:underline mb-2 inline-block">

            <i class="fas fa-arrow-left mr-1"></i>
            Back
        </a>

        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
            Add {{ $section }}
        </h1>

    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">

        <form method="POST"
              action="{{ $action }}"
              @if(isset($enctype)) enctype="{{ $enctype }}" @endif>

            @csrf

            {{-- Dynamic Fields --}}
            <div class="space-y-6">
                @yield('form-fields')
            </div>

            {{-- Buttons --}}
            <div class="mt-8 flex space-x-4">

                <button type="submit"
                        class="flex-1 bg-primary text-white px-6 py-3 rounded-lg
                               hover:bg-blue-600 transition font-semibold">

                    <i class="fas fa-save mr-2"></i>
                    Create
                </button>

                <a href="{{ url()->previous() }}"
                   class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                          px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600
                          transition font-semibold text-center">

                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection