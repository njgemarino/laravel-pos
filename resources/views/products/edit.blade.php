@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Product</h1>
        <p class="text-gray-500 mb-6">Update the product details below.</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring focus:ring-blue-200"
                       placeholder="Enter barcode">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring focus:ring-blue-200"
                       placeholder="Enter product name" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category', $product->category) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring focus:ring-blue-200"
                       placeholder="Enter category">
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring focus:ring-blue-200"
                           placeholder="0.00" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring focus:ring-blue-200"
                           placeholder="0" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('products.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection