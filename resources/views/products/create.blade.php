@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Add Product</h2>

    <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="barcode" placeholder="Barcode" class="w-full border p-2 rounded">
        <input type="text" name="name" placeholder="Product Name" class="w-full border p-2 rounded" required>
        <input type="text" name="category" placeholder="Category" class="w-full border p-2 rounded">
        <input type="number" step="0.01" name="price" placeholder="Price" class="w-full border p-2 rounded" required>
        <input type="number" name="stock" placeholder="Stock" class="w-full border p-2 rounded" required>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Product</button>
    </form>
</div>
@endsection