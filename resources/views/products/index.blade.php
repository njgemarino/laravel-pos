@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Product Management</h1>
                <p class="text-gray-500 text-sm">Manage your store products and stock levels.</p>
            </div>
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                + Add Product
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-6 border mb-6">
                    <form method="GET" action="{{ route('products.index') }}" class="grid md:grid-cols-4 gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search product name..."
                            class="rounded-lg border-slate-300">

                        <select name="category" class="rounded-lg border-slate-300">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        <select name="stock_status" class="rounded-lg border-slate-300">
                            <option value="">All Stock Status</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg">
                                Filter
                            </button>

                            <a href="{{ route('products.index') }}"
                            class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">Barcode</th>
                        <th class="p-3 text-left">Product Name</th>
                        <th class="p-3 text-left">Category</th>
                        <th class="p-3 text-left">Price</th>
                        <th class="p-3 text-left">Stock</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $product->barcode ?? '-' }}</td>
                            <td class="p-3 font-medium text-gray-800">{{ $product->name }}</td>
                            <td class="p-3">{{ $product->category ?? '-' }}</td>
                            <td class="p-3">₱{{ number_format($product->price, 2) }}</td>
                            <td class="p-3">
                                <td class="p-3">
                                @if($product->stock <= 0)
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                        Out of Stock (0)
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                                        Low Stock ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                                        In Stock ({{ $product->stock }})
                                    </span>
                                @endif
                                </td>
                            <td class="p-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection