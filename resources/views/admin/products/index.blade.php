@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
                Manage Products
            </h1>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-plus mr-2"></i>
            Add Product
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Products Table --}}
    @if($products->count() > 0)

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Name
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Price
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Category
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                </div>
                            </td>

                            {{-- Price --}}
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}
                            </td>

                            {{-- Category --}}
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->category }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-sm font-medium">

                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900">
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

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="flex justify-center mt-8">
                {{ $products->links() }}
            </div>
        @endif

    @else

        {{-- Empty State --}}
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-lg">

            <i class="fas fa-shopping-cart text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>

            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">
                No Products
            </h3>

            <a href="{{ route('admin.products.create') }}"
               class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition mt-4 inline-block">
                <i class="fas fa-plus mr-2"></i>
                Add Product
            </a>

        </div>

    @endif

</div>
@endsection